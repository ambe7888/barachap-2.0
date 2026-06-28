<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\ProviderEarningsService;
use App\Http\Services\UpdateMainOrderStatus;
use App\Jobs\SendOrderStatusChangeEmail;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\SubOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\RefundedOrder;
use App\Models\OrderCancellationPolicy;
use App\Models\UserBalance;
use App\Models\RefundOrder;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\OrderCompleteRequest;

class ProviderOrderController extends Controller
{

    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification, UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }
    public function allOrders(Request $request){

        $status = $request->input('status', 'all');
        $provider=Auth::user();
        $provider_id=$provider->id;

        $query = Order::whereHas('subOrders', function ($query) use ($provider_id){
            $query->where('provider_id',$provider_id);
        })->with('client', 'subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff');

        if ($status !== 'all') {
            $query->where('status', (int)$status);
        }

        $all_orders = $query->latest()->paginate(10);
        $total_orders = $query->count();

        // Aggregate counts by status in a single query
        $orderCounts = Order::whereHas('subOrders', function ($query) use ($provider_id) {
            $query->where('provider_id',$provider_id);
        })->selectRaw('
            COUNT(CASE WHEN status = "0" THEN 1 END) as total_pending_order,
            COUNT(CASE WHEN status = "1" THEN 1 END) as total_completed_order
        ')->first();

        return view('frontend.frontend.provider.pages.orders.all_orders', [
            'all_orders' => $all_orders,
            'total_orders' => $total_orders,
            'total_pending_order' => $orderCounts->total_pending_order,
            'total_completed_order' => $orderCounts->total_completed_order,
            'current_status' => $status,
        ]);
    }

    public function orderDetails($id,$notificationId=null){
        $order = Order::with('client','subOrders.subOrderAddons','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service')
            ->find($id);

        if (!$order) {
            abort(404);
        }

        UserNotification::where('id', $notificationId)->update(['is_read' => 'read']);

        return view('frontend.frontend.provider.pages.orders.order-details', compact('order'));
    }

    public function subOrderAddonDetails($id)
    {
        $suborder = SubOrder::with(['subOrderAddons', 'subOrderLocations.state', 'subOrderLocations.city', 'subOrderLocations.area'])->find($id);

        if (!$suborder) {
            abort(404);
        }
        return view('frontend.frontend.provider.pages.orders.suborders.sub-order-addon-details', compact('suborder'));
    }

    public function searchOrder(Request $request)
    {
        $searchString = strip_tags($request->string_search);
        $provider=Auth::user();
        $provider_id=$provider->id;

        $all_orders = Order::with([
                'client',
                'subOrders.subOrderAddons',
                'subOrders.subOrderLocations',
                'subOrders.staff'
            ])->where(function ($query) use ($searchString) {
                // Search conditions
                $query->where('total', 'LIKE', "%{$searchString}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$searchString}%");
            })
            ->whereHas('subOrders', function ($query) use($provider_id) {
                $query->where('provider_id',$provider_id);
            })
            ->latest()
            ->paginate(10);

        return $all_orders->total() >= 1 ? view('frontend.frontend.provider.pages.orders.search-order',
            compact('all_orders'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    public function paginate(Request $request)
    {

        if($request->ajax()){
            $provider=Auth::user();
            $provider_id=$provider->id;

            $query = Order::whereHas('subOrders', function ($subQuery) use($provider_id) {
                $subQuery->where('provider_id',$provider_id);
            })->with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff');

        $status = $request->query('status', 'all');

        if ($status == '0') {
            $query->where('status', 0);
        }elseif ($status ==  '2') {
            $query->where('status', 2);
        }elseif ($status ==  '4') {
            $query->where('status', 4);
        }

        $all_orders = $query->latest()->paginate(10);

            return view('frontend.frontend.provider.pages.orders.search-order', compact('all_orders'))->render();
        }
    }


    public function orderAcceptDecline(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'sub_order_id' => 'required|integer',
            'status_id' => 'required|integer',
        ]);
        $status =(int)$request->input('status_id');

        $provider_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);
     
       

        $sub_order = SubOrder::with('client','provider')
            ->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('provider_id', $provider_id)
            ->whereIn('status', [0, 1]) // pending or active
            ->first();
        
        if (empty($sub_order) || empty($order)) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Order not found.')));
          
        }

        if(!empty($sub_order)){
            // update sub order status
            if($status == 5)
            {

                $result=$this->declineOrder($sub_order);
           
                $result=$result->getOriginalContent();
                
               if($result['success'] == false)
               {

                return redirect()->back()->with(FlashMsg::item_delete($result['message']));
                  
               }

            }
            else
            {
                $sub_order->status = (int) $status; // approved
                $sub_order->save();
            }
          
            // Use the action to update the main order status
            $this->updateMainOrderStatus->execute($order, $sub_order);

            // Create order status notifications
            try {

                if (!empty($sub_order)) {
                    $this->orderServiceNotification->orderStatusChanceNotification($sub_order);
                }

                $emailData = [
                    'order_id' => $request->order_id,
                    'sub_order_id' => $request->sub_order_id,
                    'provider_id' => $provider_id,
                    'client_email' => $sub_order->client?->email,
                    'provider_email' => $sub_order->provider?->email,
                    'admin_email' => get_static_option('site_global_email'),
                    'status' => (int)$status,
                ];

                // Dispatch job to send email in the background
                dispatch(new SendOrderStatusChangeEmail($emailData));

            }catch (\Exception $e) {}

            // 1= active or 5 = decline
            if ($status == 1){
                return redirect()->back()->with(FlashMsg::item_new(__('order accepted success')));
               
            }elseif($status == 5){
                return redirect()->back()->with(FlashMsg::item_new(__('order decline success')));
              
            }else{

                return redirect()->back()->with(FlashMsg::item_new(__('order status changed')));
               
            }
        }
    }

    public function declineOrder($suborder){
       
        
        $refunded_amount=0;
        $order_id=$suborder->order_id;
        $order=Order::where('id',$order_id)->first();
        $old_status=$suborder->status;
       
       
        $message="";
        $success="";

        if($suborder->status== 5)
        {
           
            $success =false;
            $message =__("Order for this ".$suborder->service?->title." already declined");
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);
        }
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==1) )
        {
           
            if($suborder->is_refunded==1)
            {
                $success =false;
                $message =__("Order for this ".$suborder->service?->title." already refunded");
                $result=[
                    'success' => $success,
                    'message' => $message
                ];
                return response()->json($result);
            }
        }
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==0))
        {
           
            $refunded_amount=$suborder->total;
            $suborder->status=5;
            $suborder->is_refunded=1;
            $suborder->save();

            $refunded_order=RefundedOrder::updateOrCreate(
                [
                    'order_id'=>$suborder->order_id,
                    'sub_order_id'=>$suborder->id,
                    'client_id'=>$suborder->client_id,
                    'provider_id'=>$suborder->provider_id,
                ],[
                
                'amount'=>$refunded_amount,
                'user_type'=> 0,
                'current_suborder_status' => $old_status,
                'type'=> 1,
                'status'=> 0,
                'add_fee' => 0
            ]);
            $messgae="Order Decline successfully";
            $success="true";
            $result=[
                'success' => $success,
                'message' => $messgae
            ];
            return response()->json($result);

                
            
        }
        else if($suborder->payment_status!='complete')
        {
    
            $suborder->status=5;
            $suborder->is_refunded=0;
            $suborder->save();
            
            $message="Order Decline successfully";
            $success="true";
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);
              
            
        }    
       

    }


    public function OrderStatusChangeCancel(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
            'status_id' => 'required',
        ]);

        $provider_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        if (empty($order)) {

            return redirect()->back()->with(FlashMsg::item_delete(__('Order not found.')));
        }

        $sub_order = SubOrder::with('client','provider')
            ->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('provider_id', $provider_id)
            ->whereIn('status', [1, 3])
            ->first();

        if (empty($sub_order) || empty($order)) {

            return redirect()->back()->with(FlashMsg::item_delete(__('Order not found.')));
        }

        if(!empty($sub_order)){
            // update sub order status
            $result=$this->cancelOrder($sub_order);
            $result=$result->getOriginalContent();
             
            if($result['success'] == false)
            {
                return redirect()->back()->with(FlashMsg::item_delete($result['message']));
            }
            // Use the action to update the main order status
            $this->updateMainOrderStatus->execute($order, $sub_order);

            // Create order status notifications
            try {
                if (!empty($sub_order)) {
                    $this->orderServiceNotification->orderStatusChanceNotification($sub_order);
                }

                $emailData = [
                    'order_id' => $request->order_id,
                    'sub_order_id' => $request->sub_order_id,
                    'provider_id' => $provider_id,
                    'client_email' => $sub_order->client?->email,
                    'provider_email' => $sub_order->provider?->email,
                    'admin_email' => get_static_option('site_global_email'),
                    'status' => 4,
                ];

                // Dispatch job to send email in the background
                dispatch(new SendOrderStatusChangeEmail($emailData));

            }catch (\Exception $e) {}


            return redirect()->back()->with(FlashMsg::item_new(__('order status changed to cancel')));

        }
    }

    public function cancelOrder($suborder){
       
        
        $refunded_amount=0;
        $order_id=$suborder->order_id;
        $order=Order::where('id',$order_id)->first();
        $old_status=$suborder->status;
       
       
        $message="";
        $success="";

        if($suborder->status== 4)
        {
           
            $success =false;
            $message =__("Order for this ".$suborder->service?->title." already canceled");
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);
        }
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==1) )
        {
           
            if($suborder->is_refunded==1)
            {
                $success =false;
                $message =__("Order for this ".$suborder->service?->title." already refunded");
                $result=[
                    'success' => $success,
                    'message' => $message
                ];
                return response()->json($result);
            }
        }
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==0))
        {
           
            $refunded_amount=$suborder->total;
            $suborder->status=4;
            $suborder->is_refunded=1;
            $suborder->save();

            $refunded_order=RefundedOrder::updateOrCreate(
                [
                    'order_id'=>$suborder->order_id,
                    'sub_order_id'=>$suborder->id,
                    'client_id'=>$suborder->client_id,
                    'provider_id'=>$suborder->provider_id,
                ],[
               
                'amount'=>$refunded_amount,
                'user_type'=> 0,
                'current_suborder_status' => $old_status,
                'type'=> 0,
                'status'=> 0,
                'add_fee' => 0
            ]);
            $messgae="Order Cancel successfully";
            $success="true";
            $result=[
                'success' => $success,
                'message' => $messgae
            ];
            return response()->json($result);

                
            
        }
        else if($suborder->payment_status!='complete')
        {
    
            $suborder->status=4;
            $suborder->is_refunded=0;
            $suborder->save();
            
            $message="Order Cancel successfully";
            $success="true";
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);
              
            
        }    
       

    }



    public function orderCompleteRequest(Request $request,$sub_order_id)
    {


        $provider_id = Auth::guard('sanctum')->user()->id;
        $sub_order = SubOrder::where('id',$request->sub_order_id)->first();
        $order = Order::with('client')->where('id',$sub_order->order_id)->first();
       

        // first check if already request created
        $order_completed_request_check = OrderCompleteRequest::where('order_id', $order->id)
            ->where('sub_order_id', $sub_order_id)
            ->where('provider_id', $provider_id)
            ->latest()
            ->first();

        if (!empty($order_completed_request_check)){
            if ($order_completed_request_check->status == 1) {
                return redirect()->back()->with(FlashMsg::item_new(__('Order already completed.')));
               
            }

            if ($order_completed_request_check->status == 0) {

                return redirect()->back()->with(FlashMsg::item_new(__('Order complete request already been created and is pending.')));
               
            }
        }

      

        if (empty($sub_order) || empty($order)){

            return redirect()->back()->with(FlashMsg::item_delete(__('Order not found.')));
           
        }

        // if cancel order
        if($sub_order->is_refunded === 1){

            return redirect()->back()->with(FlashMsg::item_delete(__('You can not change status because earlier you canceled the order')));
           
        }


        // if order status not 2 and order payment status is complete
        if($sub_order->status != 2 && $order->payment_status == 'complete'
            || $sub_order->status != 2 && $order->payment_gateway == 'cash_on_delivery'){
                    // update sub order
                    $sub_order->update([
                        'complete_request' => 1,
                        'payment_status' => 'complete',
                    ]);

                    OrderCompleteRequest::create([
                        'order_id' => $sub_order->order_id,
                        'sub_order_id' => $sub_order->id,
                        'client_id' => $sub_order->client_id,
                        'provider_id' => $sub_order->provider_id,
                        'message'=> __('Not decline or complete yet. Please wait'),
                        'status' => 0,
                    ]);


                    //Send email after change status to client
                    try {
                        $message_body_client =__('Hello,') . ' ' .$order->client?->fullname . ' ' . __('A new request is created for complete an order.').'</br>' . ' <span class="verify-code">'.__('Order ID is:') . $order->id. '</span>';
                        $message_body_admin =__('Hello Admin A new request is created for complete an order.').'</br>' . ' <span class="verify-code">'.__('Order ID is:') . $order->id. '</span>';

                        Mail::to($order->client?->email)->send(new BasicMail([
                            'subject' => __('New Request For Complete an Order'),
                            'message' => $message_body_client
                        ]));

                        Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                            'subject' => __('New Request For Complete an Order'),
                            'message' => $message_body_admin
                        ]));

                    } catch (\Exception $e) {}


                    return redirect()->back()->with(FlashMsg::item_new(__('Your request submitted. Client will complete your request after review.')));

                    
                
        }else{

            return redirect()->back()->with(FlashMsg::item_delete(__('You can not change order status because this order already completed & order status due to payment status pending.')));
        }
    }
  
   
    // public function change_payment_status($id){

    //     $order= Order::with('client','subOrders.provider')->where('id',$id)->first();

    //     if (!$order) {
    //         return redirect()->back()->with(FlashMsg::item_new('Order not found.'));
    //     }

    //     $old_status = $order->payment_status;

    //     if($order->payment_status=='pending'){
    //         $new_status = 'complete';
    //     }else{
    //         $new_status = 'pending';
    //     }

    //     Order::where('id',$id)->update([
    //         'payment_status'=> $new_status
    //     ]);

    //     SubOrder::where('order_id', $id)->update([
    //         'payment_status' => $new_status
    //     ]);

    //     try {
    //         // mail send to client
    //         $message = get_static_option('admin_change_payment_status_message') ?? __('Payment Status Changed.');
    //         $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$order->client?->fullname,$old_status,$new_status,$order->id],$message);
    //         Mail::to($order->client?->email)->send(new BasicMail([
    //             'subject' => get_static_option('admin_change_payment_status_subject') ?? __('Payment Status Changed.'),
    //             'message' => $message
    //         ]));

    //         // mail send to provider
    //         foreach ($order->subOrders as $subOrder) {
    //             $provider = $subOrder->provider;
    //             $message = get_static_option('admin_change_payment_status_message') ?? __('Payment Status Changed.');
    //             $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$provider->email,$old_status,$new_status,$order->id],$message);

    //             Mail::to( $provider->email)->send(new BasicMail([
    //                 'subject' => get_static_option('admin_change_payment_status_subject') ?? __('Payment Status Changed.'),
    //                 'message' => $message
    //             ]));
    //         }

    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(FlashMsg::item_new($e->getMessage()));
    //     }

    //     return redirect()->back()->with(FlashMsg::item_new('Status Change Success'));
    // }

}
