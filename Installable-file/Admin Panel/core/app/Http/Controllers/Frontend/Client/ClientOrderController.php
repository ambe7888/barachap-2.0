<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserNotification;
use App\Models\SubOrder;
use App\Models\OrderCompleteRequest;
use App\Http\Services\UpdateMainOrderStatus;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\ProviderEarningsService;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Jobs\SendOrderStatusChangeEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Validator;
use App\Models\RefundedOrder;
use App\Jobs\SendOrderCancelEmailForWeb;
use App\Models\OrderCancellationPolicy;
use App\Models\RefundGateway;
use Illuminate\Support\Carbon;
use App\Jobs\SendRefundRequestEmailForWeb;
use App\Models\Backend\AdminNotification;
use App\Models\Service;
use App\Models\Staff;
use Modules\Coupon\app\Models\Coupon;




class ClientOrderController extends Controller
{

    protected $providerEarningsService;
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(ProviderEarningsService $providerEarningsService, OrderServiceNotification $orderServiceNotification, UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->providerEarningsService = $providerEarningsService;
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }
    public function allOrders()
    {
        $client_id = auth('sanctum')->user()->id;
        $all_orders = Order::where('user_id', $client_id)->with('subOrders')->latest()->paginate(10);
        return view('frontend.frontend.client.pages.order.all-order', compact('all_orders'));
    }


    public function searchOrder(Request $request)
    {
        $searchString = strip_tags($request->string_search);
        $client_id = auth('sanctum')->user()->id;

        $all_orders = Order::with([
                'subOrders.subOrderAddons',
                'subOrders.subOrderLocations',
                'subOrders.staff'
            ])->where(function ($query) use ($searchString) {
                // Search conditions
                $query->where('total', 'LIKE', "%{$searchString}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$searchString}%");
            })
            ->whereHas('subOrders', function ($query,$client_id) {
                $query->where('client_id',$client_id);
            })
            ->latest()
            ->paginate(10);

        return $all_orders->total() >= 1 ? view('frontend.frontend.client.pages.order.search-order',
            compact('all_orders'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    public function paginate(Request $request)
    {

        if($request->ajax()){

            $client_id = auth('sanctum')->user()->id;

            $query = Order::whereHas('subOrders', function ($subQuery,$client_id) {
                $subQuery->where('client_id', '!=', $client_id);
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

            return view('frontend.frontend.client.pages.order.search-order', compact('all_orders'))->render();
        }
    }


    public function orderDetails($id,$notificationId=null){
        $order = Order::with('subOrders.subOrderAddons','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service','subOrders')
            ->find($id);

        if (!$order) {
            abort(404);
        }

        UserNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        $current_date = now()->format('Y-m-d');
        $coupon_code=Coupon::where('expire_date','>',$current_date)->get();

        return view('frontend.frontend.client.pages.order.order-details', compact('order','coupon_code'));
    }


    public function subOrderDetails($id)
    {
        $suborder = SubOrder::with(['subOrderAddons','subOrderLocations','subOrderLocations.state', 'subOrderLocations.city', 'subOrderLocations.area','service','provider','admin'])->find($id);

        if (!$suborder) {
            abort(404);
        }

        $client_id= auth('sanctum')->user()->id;
        $send_complete_request = false;

        $order_complete_request= OrderCompleteRequest::where('sub_order_id',$id)->where('client_id',$client_id)->first();
        $gateway_methods=RefundGateway::all();
        $order=Order::where('id',$suborder->order_id)->first();
        $order_payment_status=$order->payment_status;
        $review_details=null;

        foreach($suborder->reviews as $review)
        {
            if($review->reviewer_id == $client_id)
            {
                $review_details=$review;
                break;
            }
        }


        return view('frontend.frontend.client.pages.order.sub-order-details', compact('suborder','order_complete_request','gateway_methods','order_payment_status','review_details'));
    }


    public function orderCompleteRequestApprove(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
        ]);

        $client_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        $sub_order = SubOrder::with('provider', 'client','order')
            ->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('client_id', $client_id)
            ->first();

        if (empty($sub_order) || empty($order)) {

             return response()->json([
                'status'=>'failed',
                'msg'=>__('Order Not Found'),
            ]);


        }

        // order request complete find
        $order_complete_request = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('client_id', $client_id)
            ->latest()
            ->first();

        if (empty($order_complete_request)) {

             return response()->json([
                'status'=>'failed',
                'msg'=>__('Order Complete Request Not Found'),
            ]);



        }

        if(!empty($sub_order)){
            // update sub order
            $sub_order->update([
                'complete_request' => 2,
                'status'=> 2,
            ]);

            // update order request complete table update
            $order_complete_request->update([
                'status'=> 1,
                'message'=> __('Your order complete request have accepted.'),
            ]);

            // update main order status
            $this->updateMainOrderStatus->execute($order, $sub_order);
            if($order->payment_gateway === 'cash_on_delivery')
            {
                // If payment gateway is cash on delivery, update the order's payment status
                $order->payment_status = 'complete';
                $order->save();
            }

            try {
                // Update provider earnings using the service class
                $this->providerEarningsService->updateProviderEarnings($sub_order);

                // Create order status notifications
                if (!empty($sub_order)) {
                    $this->orderServiceNotification->orderStatusChanceNotification($sub_order);
                }

                $emailData = [
                    'order_id' => $request->order_id,
                    'sub_order_id' => $request->sub_order_id,
                    'provider_id' => $sub_order->provider?->id,
                    'client_email' => $sub_order->client?->email,
                    'provider_email' => $sub_order->provider?->email,
                    'admin_email' => get_static_option('site_global_email'),
                    'status' => 2,
                ];

                // Dispatch job to send email in the background
                dispatch(new SendOrderStatusChangeEmail($emailData));

            }catch (\Exception $e) {}

            return response()->json([
                'status'=>'success',
                'msg'=>__('Sub-Order complete request successfully approved.'),
            ]);

        }else{

           return response()->json([
                'status'=>'failed',
                'msg'=>__('Sub-Order not found.'),
            ]);

        }
    }

    public function orderCompleteRequestDecline(Request $request)
    {

       $validator = Validator::make($request->all(),[
            'order_id' => 'required',
            'sub_order_id' => 'required',
            'decline_reason' => 'required|min:10|max:1000',
        ],[
            'order_id.required' => 'Order Id is required',
            'sub_order_id.required' => 'Sub Order Id is required',
            'decline_reason' => 'Decline reason is required'
        ]);


        if ($validator->fails()) {
            return response()->json([
              'status' => 'validation_error',
              'errors' => $validator->errors(), // Send all errors
            ]);
        }

        $client_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        $sub_order = SubOrder::with('provider')->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('client_id', $client_id)
            ->first();

        if (empty($sub_order) || empty($order)) {

            return response()->json([
                'status' => 'error'
            ]);

        }

        // order request complete find
        $order_complete_request = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('client_id', $client_id)
            ->latest()
            ->first();

        if (empty($order_complete_request)) {

          return response()->json([
                'status' => 'error'
            ]);

        }

        if(!empty($sub_order)){
            // update sub order
            $sub_order->update([
                'complete_request' => 3,
            ]);

            // update order request complete table update
            $order_complete_request->update([
                'message'=> $request->decline_reason,
                'status'=> 2,
            ]);



            //Send decline mail to provider and admin
            try {

                // Create order status notifications
                if (!empty($sub_order)) {
                    $this->orderServiceNotification->orderStatusChanceNotification($sub_order);
                }

                $message = __('A client has been decline a request to complete an order. Order ID #'). $request->order_id.'</br>';
                $message = str_replace(["@order_id"],[$request->order_id],$message);

                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' =>get_static_option('buyer_order_decline_subject') ?? __('Order Complete Decline'),
                    'message' => $message
                ]));

                $message = __('Your request to complete an order has been decline by the client. Order ID #'). $request->order_id.'</br>';
                $message = str_replace(["@order_id"],[$request->order_id],$message);
                Mail::to($sub_order->provider?->email)->send(new BasicMail([
                    'subject' =>get_static_option('buyer_order_decline_subject') ?? __('Order Complete Decline'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {
            }

           return response()->json([
                'status' => 'success'
            ]);


        }else{

           return response()->json([
                'status' => 'error'
            ]);

        }
    }


    public function cancelOrder(Request $request){

        $order_id=$request->order_id_for_cancel;
        $sub_order_id=$request->sub_order_id_for_cancel;
        $cancel_reason=$request->cancel_reason;
        $gateway_method=$request->gateway_method;
        $method_fields=$request->method_fields;
        $method_fields=json_encode($method_fields,true);
        $refunded_amount=0;
        if(!$order_id)
        {
            return response()->json([
               'status' => 'failed',
               'message' => __("Order id required")
            ], 200);
        }
        if(empty($sub_order_id))
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Sub order id required")
            ], 200);
        }

         if(empty($cancel_reason))
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Cancel Reason is required")
            ], 200);
        }


        $time_expired="true";
        $message="";
        $status="";

        $cancellation_policy=OrderCancellationPolicy::first();
        if(empty($cancellation_policy))
        {
            return response()->json([
                 'status' => 'failed',
                'message' => __("Cancellation policy not found")
            ], 200);
        }
        $available_type=$cancellation_policy->available_type;
        $order=Order::where('id',$order_id)->first();
        if($available_type=='certain_time')
        {

            $cancel_time=$cancellation_policy->time_in_min;
            $current_time=Carbon::now();
            $order_created_time=Carbon::parse($order->created_at);
            $diff_in_minutes=$current_time->diffInMinutes($order_created_time);
            if($diff_in_minutes<=$cancel_time)
            {
                $time_expired="false";

            }
            else
            {
                return response()->json([
                     'status' => 'failed',
                    'message' => __("You can cancel the order within ".$cancel_time." minutes")
                ], 200);
            }
        }



        $sub_order=SubOrder::where("id",$sub_order_id)->first();
        if($sub_order)
        {
                $old_status=$sub_order->status;
                if($sub_order->status== 4)
                {
                    return response()->json([
                        'status' => 'failed',
                        'message' => __("Order for this ".$sub_order->service?->title." already canceled")
                    ], 200);
                }
                else if(($sub_order->payment_status=='complete' && $sub_order->status != 2 && $sub_order->status != 3))
                {
                    if($available_type=='certain_time')
                    {
                        if($time_expired =="false")
                        {
                            if( $cancellation_policy->fine_type =="flat")
                            {
                                $refunded_amount=$sub_order->total - $cancellation_policy->amount;
                            }
                            else if( $cancellation_policy->fine_type =="percentage")
                            {
                                $refunded_amount=$sub_order->total - ($sub_order->total * $cancellation_policy->amount/100);
                            }
                            $sub_order->status=4;
                            $sub_order->is_refunded=1;
                            $sub_order->save();
                            // update main order status
                            $this->updateMainOrderStatus->execute($order, $sub_order);

                            $refunded_order=RefundedOrder::updateOrCreate([
                                'order_id'=>$sub_order->order_id,
                                'sub_order_id'=>$sub_order->id,
                                'client_id'=>$sub_order->client_id,
                                'provider_id'=>$sub_order->provider_id,
                                'admin_id'=>$sub_order->admin_id,

                                ],[

                                'amount'=>$refunded_amount,
                                'gateway_id'=>$gateway_method,
                                'gateway_fields'=>$method_fields,
                                'cancel_reason'=>$cancel_reason ?? "",
                                'user_type'=> 1,
                                'current_suborder_status' => $old_status,
                                'type'=> 0,
                                'status'=> 0,
                                'add_fee' => 1
                            ]);
                            $messgae="Order Cancel successfully";
                            $status="success";

                        }
                    }
                    else
                    {

                        if( $cancellation_policy->fine_type =="flat")
                        {
                            $refunded_amount=$sub_order->total - $cancellation_policy->amount;
                        }
                        else if( $cancellation_policy->fine_type =="percentage")
                        {
                            $refunded_amount=$sub_order->total - ($sub_order->total * $cancellation_policy->amount/100);
                        }
                        $sub_order->status=4;
                        $sub_order->is_refunded=1;
                        $sub_order->save();
                        // update main order status
                        $this->updateMainOrderStatus->execute($order, $sub_order);
                        $refunded_order=RefundedOrder::updateOrCreate(
                            [
                                'order_id'=>$sub_order->order_id,
                                'sub_order_id'=>$sub_order->id,
                                'client_id'=>$sub_order->client_id,
                                'provider_id'=>$sub_order->provider_id,
                                'admin_id'=>$sub_order->admin_id,
                            ],[

                            'amount'=>$refunded_amount,
                            'gateway_id'=>$gateway_method,
                            'gateway_fields'=>$method_fields,
                            'cancel_reason'=>$cancel_reason ?? "",
                            'user_type'=> 1,
                            'current_suborder_status' => $old_status,
                            'type'=> 0,
                            'status'=> 0,
                            'add_fee' => 1
                        ]);
                        $messgae="Order Cancel successfully";
                        $status="success";

                    }
                }
                else if(($sub_order->payment_status!='complete' && $sub_order->status != 2 && $sub_order->status != 3))

                {
                    if($available_type=='certain_time')
                    {

                        if($time_expired=="false")
                        {
                            $sub_order->status=4;
                            $sub_order->is_refunded=0;
                            $sub_order->save();
                            // update main order status
                            $this->updateMainOrderStatus->execute($order, $sub_order);

                            $message="Order Cancel successfully";
                            $status="success";


                        }


                    }
                    else
                    {
                        $sub_order->status=4;
                        $sub_order->is_refunded=0;
                        $sub_order->save();
                        // update main order status
                        $this->updateMainOrderStatus->execute($order, $sub_order);

                        $message="Order Cancel successfully";
                        $status="success";


                    }


                }
                else
                {
                    $message="Order for this ".$sub_order->service?->title."already completed or canceled";
                    $status="failed";
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ], 200);
                }



            if($status=="success")
            {

                    try {

                        // Cancel order notifications
                       $this->orderServiceNotification->cancelOrderNotificationForWeb($order_id,$sub_order_id,$request);
                        // Dispatch job to send email in the background
                        $order_details = Order::with('subOrders')->find($order_id);
                        dispatch(new SendOrderCancelEmailForWeb($order_details,$sub_order_id));

                    }catch (\Exception $exception){

                    }

                    try{
                        if(moduleExists('WhatsAppBookingSystem'))
                        {
                            $suborder=SubOrder::where('id',$sub_order_id)->first();
                            // Send WhatsApp message
                            $receiverNumber = Auth::guard('sanctum')->user()->phone;
                            $message = __('You have successfully canceled service #') .$suborder?->title.__('  for order #').$order_id;
                            dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));


                            // Get unique provider emails
                            $provider = User::where('id', $suborder->provider_id)->first();
                            // Send order email to provider
                            if ($provider) {

                                if($sub_order->provider_id === $provider->id){
                                    $message = __('You have successfully canceled service #') . $suborder?->title . __(' for order #') . $order_id;
                                } else {
                                    $message = __('You have a new order cancellation #') . $order_id;
                                }

                                $receiverNumber = $provider?->phone;
                                dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));

                            }
                        }
                    }catch (\Exception $exception){

                    }

                    $order_wise_all_suborder_is_refund = SubOrder::where('order_id', $order->id)
                    ->pluck('is_refunded')
                    ->toArray();

                    // Step 1: If all sub-order is_refund status is 1, set main order is_refund status to 1
                    if (!in_array(0, $order_wise_all_suborder_is_refund)){
                        $order->is_refunded = 1;
                    } else {
                        // otherwise 0
                        $order->is_refunded = 0;
                    }

                    $order->save();


                    $message="Order Cancel successfully";
                    return response()->json([
                        'status' => 'success',
                        'message' => $message
                    ], 200);
            }
            else
            {
                return response()->json([
                    'status' => 'success',
                    'message' => $message
                ], 200);
            }




        }

    }

    public function refundRequestSend(Request $request)
    {
        $user=Auth::guard('sanctum')->user();
        $user_id=$user->id;
        $order_id=$request->order_id_for_refund;
        $sub_order_id=$request->sub_order_id_for_refund;
        $refund_reason=$request->refund_reason;
        $gateway_method=$request->gateway_method_for_refund;
        $method_fields=$request->method_fields;
        $method_fields=json_encode($method_fields,true);
        $refunded_amount=0;
        if(!$order_id)
        {
            return response()->json([
               'status' => 'failed',
               'message' => __("Order id required")
            ], 200);
        }
        else if(!$sub_order_id)
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Sub order id required")
            ], 200);
        }
        else if(!$refund_reason)
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Refund Reason is required")
            ],200);
        }

        $sub_order=SubOrder::where('id',$sub_order_id)->where("client_id",$user_id)->first();

        $order=Order::where('id',$order_id)->first();
        if(!$order)
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Order not found")
            ], 200);
        }
        if(!$sub_order)
        {
            return response()->json([
                'status' => 'failed',
                'message' => __("Sub order not found")
            ], 200);
        }

        $refunded_order=null;

        //if sub order status and payment status check wheteher complete or not

        $old_status=$sub_order->status;
        if(($sub_order->status==2 || $sub_order->status== 3 ) && $order->payment_status=="complete") // 2="complete 3="delivered"
        {
            if($sub_order->provider_id)
            {
                $complete_order_balance_with_tax = $sub_order->total; // Total amount including tax
                $complete_order_tax = $sub_order->tax;  // Tax amount
                $admin_commission_amount = $sub_order->commission_amount;   // Admin commission amount

                // Calculate order balance without tax
                $complete_order_balance_without_tax = $complete_order_balance_with_tax - $complete_order_tax;
                // Calculate provider's earning amount
                $refunded_amount  = $complete_order_balance_without_tax - $admin_commission_amount;



                $refunded_order=RefundedOrder::updateOrCreate(
                    [
                        'order_id'=>$sub_order->order_id,
                        'sub_order_id'=>$sub_order->id,
                        'client_id'=>$sub_order->client_id,
                        'provider_id'=>$sub_order->provider_id,
                    ],[

                    'amount'=>$refunded_amount,
                    'gateway_id'=>$gateway_method,
                    'gateway_fields'=>$method_fields,
                    'cancel_reason'=>$cancel_reason ?? "",
                    'user_type'=> 1,
                    'current_suborder_status' => $old_status,
                    'type'=> 0,
                    'status'=> 0,
                    'add_fee' => 0
                ]);


            }
            else if($sub_order->admin_id)
            {
                $complete_order_balance_with_tax = $sub_order->total; // Total amount including tax
                $complete_order_tax = $sub_order->tax;  // Tax amount

                // Calculate order balance without tax
                $refunded_amount= $complete_order_balance_with_tax - $complete_order_tax;

                $refunded_order=RefundedOrder::updateOrCreate([
                    'order_id'=>$sub_order->order_id,
                    'sub_order_id'=>$sub_order->id,
                    'client_id'=>$sub_order->client_id,
                    'admin_id'=>$sub_order->admin_id,

                    ],[

                    'amount'=>$refunded_amount,
                    'gateway_id'=>$gateway_method,
                    'gateway_fields'=>$method_fields,
                    'cancel_reason'=>$cancel_reason ?? "",
                    'status'=>1,
                    'user_type'=> 1,
                    'current_suborder_status' => $old_status,
                    'type'=> 0,
                    'add_fee' => 0
                ]);
                $sub_order->update([
                    'status'=>6,//6=refunded
                    'is_refunded' =>1
                ]);
                // update main order status
                $this->updateMainOrderStatus->execute($order,$sub_order);

            }



        }
        else
        {
            return response()->json([
                'status' => "failed",
                'message' => __("Sub order ".$sub_order->id." not refundable")
            ], 200);
        }



        if($refunded_order)
        {
            try {

                // Cancel order notifications
                $this->orderServiceNotification->cancelOrderNotificationForWeb($order_id,$sub_order_id,$request);
                // Dispatch job to send email in the background
                $order_details = Order::with('subOrders')->find($order_id);
                dispatch(new SendRefundRequestEmailForWeb($order_details,$sub_order_id));
            }catch (\Exception $exception){


            }

            return response()->json([
                'status' => "success",
                'message' => __("Refund request send successfully")
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => "failed",
                'message' => __("Refund request not send")
            ], 200);
        }

    }


      public function serviceDetails($id){

        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->find($id);
        $admin_id=$service->admin_id;
        $provider_id=$service->provider_id;
        $service_staffs=$service->staffs_id;
        if($service_staffs)
        {
            $staffs=[];
            if($service->disable_staff==1)
            {
                $service_staffs=explode(',',$service_staffs);
                foreach($service_staffs as $staff)
                {
                $staffs[]=Staff::where('id',$staff)->first();
                }
            }
        }
        else
        {
            if($service->disable_staff==1)
            {
                if($admin_id)
                {
                    $staffs=Staff::where('admin_id',$admin_id)->get();
                }
                else if($provider_id)
                {
                    $staffs=Staff::where('provider_id',$provider_id)->get();
                }

            }
            else{
                $staffs=[];
            }
        }
        if (!$service) {
            try {

            }catch (\Exception $exception){}

            abort(404);
        }

        return view('frontend.frontend.client.pages.order.order-service-details', compact('service', 'staffs'));
    }










}
