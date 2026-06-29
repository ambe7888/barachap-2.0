<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\ProviderEarningsService;
use App\Http\Services\ProviderEarningServiceUpdate;

use App\Http\Services\UpdateMainOrderStatus;
use App\Jobs\SendOrderStatusChangeEmail;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Order;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\RefundedOrder;
use App\Models\OrderCancellationPolicy;
use App\Models\UserBalance;
use App\Models\RefundOrder;

class AdminServiceOrderManageController extends Controller
{
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;
    protected $providerEarningsService;
    protected $providerEarningsServiceUpdate;

    public function __construct(OrderServiceNotification $orderServiceNotification, UpdateMainOrderStatus $updateMainOrderStatus,ProviderEarningsService $providerEarningsService ,ProviderEarningServiceUpdate $providerEarningsServiceUpdate)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
        $this->providerEarningsService = $providerEarningsService;
        $this->providerEarningsServiceUpdate = $providerEarningsServiceUpdate;

    }

    public function orderSettings()
    {
        return view('backend.pages.orders.order-settings');
    }
    public function updateOrderSettings(Request $request)
    {
        $this->validate($request, [
            'bill_to_title' => 'nullable|string',
            'ship_to_title' => 'nullable|string',
            'invoice_title' => 'nullable|string',
            'invoice_no_title' => 'nullable|string',
            'order_invoice_notes' => 'nullable|string',
        ]);

        $fields = [
            'bill_to_title',
            'ship_to_title',
            'invoice_title',
            'invoice_no_title',
            'order_invoice_notes',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function allAdminOrders(Request $request){

        $status = $request->input('status', 'all');

        $query = Order::whereHas('subOrders', function ($query) {
            $query->whereNotNull('admin_id');
        })->with('client', 'subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff');

        if ($status !== 'all') {
            $query->where('status', (int)$status);
        }

        $all_orders = $query->latest()->paginate(10);
        $total_orders = $query->count();

        // Aggregate counts by status in a single query
        $orderCounts = Order::whereHas('subOrders', function ($query) {
            $query->whereNotNull('admin_id');
        })->selectRaw('
            COUNT(CASE WHEN status = "0" THEN 1 END) as total_pending_order,
            COUNT(CASE WHEN status = "1" THEN 1 END) as total_completed_order
        ')->first();

        return view('backend.pages.orders.admin-orders.all_orders', [
            'all_orders' => $all_orders,
            'total_orders' => $total_orders,
            'total_pending_order' => $orderCounts->total_pending_order,
            'total_completed_order' => $orderCounts->total_completed_order,
            'current_status' => $status,
        ]);
    }

    public function orderAdminDetails($id,$notificationId=null){
        $order = Order::with('client','subOrders.subOrderAddons','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service')
            ->find($id);

        if (!$order) {
            abort(404);
        }

        AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);

        return view('backend.pages.orders.order-details', compact('order'));
    }

    public function adminSubOrderAddonDetails($id)
    {
        $suborder = SubOrder::with(['subOrderAddons', 'subOrderLocations.state', 'subOrderLocations.city', 'subOrderLocations.area'])->find($id);

        if (!$suborder) {
            abort(404);
        }
        return view('backend.pages.orders.suborders.sub-order-addon-details', compact('suborder'));
    }

    public function searchOrder(Request $request)
    {
        $searchString = strip_tags($request->string_search);

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
            ->whereHas('subOrders', function ($query) {
                $query->where('admin_id', '!=', null);
            })
            ->latest()
            ->paginate(10);

        return $all_orders->total() >= 1 ? view('backend.pages.orders.user-orders.search-order',
            compact('all_orders'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    public function paginate(Request $request)
    {

        if($request->ajax()){

            $query = Order::whereHas('subOrders', function ($subQuery) {
                $subQuery->where('admin_id', '!=', null);
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

            return view('backend.pages.orders.user-orders.search-order', compact('all_orders'))->render();
        }
    }

    public function changeAdminStatus(Request $request){
        
        $order_id = $request->id;
        $status = $request->status_id;
        $order = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.provider', 'subOrders.staff')
            ->where('id',$order_id)
            ->first();

        if (!$order) {
            abort(404);
        }

        $current_status = $order->status;
        $old_status = '';
        $pending = __('Pending');
        $active = __('Active');
        $completed = __('Completed');
        $cancel = __('Cancel');

        // old order status
        if ($current_status == 0){
            $old_status = $pending;
        }elseif($current_status == 1){
            $old_status = $active;
        }elseif ($current_status == 2){
            $old_status = $completed;
        }elseif ($current_status == 3){
            $old_status = $cancel;
        }

        // new order status
        if($order->status==0){
            $new_status = 'Active';
        }elseif($order->status==1){
            $new_status = 'Completed';
        }else{
            $new_status = 'Cancel';
        }

        $client_email = optional($order->client)->email;
        // update
        $order->update(['status' => $status]);
        SubOrder::where('order_id', $order_id)->update([
            'status' => $status
        ]);

        // if order status change mail send to client and provider
        try {
            $order_status_change_title = __('Order Status Changed.') . $order->id;
            $message_status = __('Order Status Changed.'). ' ' . __('Order ID:') .$order->id;
            $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$order->name,$old_status,$new_status,$order->id],$message_status);
            Mail::to($client_email)->send(new BasicMail([
                'subject' => $order_status_change_title,
                'message' => $message
            ]));

            // sent to order provider mail multiple provider
            foreach ($order->subOrders as $provider){
                $provider_email = $provider->provider?->email;
                $provider_name = $provider->provider?->fullname;

                $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$provider_name,$old_status,$new_status,$order->id],$message_status);
                Mail::to($provider_email)->send(new BasicMail([
                    'subject' => $order_status_change_title,
                    'message' => $message
                ]));
            }

            //send whatsapp message
            if(moduleExists('WhatsAppBookingSystem'))
            {
                $client_phone = optional($order->client)->phone;
                if($client_phone)
                {
                    $message_status= __('Your order status has been changed from @old_status to @new_status. Order ID: @order_id');
                    $whatsapp_message = str_replace(["@old_status","@new_status","@order_id"],[$old_status,$new_status,$order->id],$message_status);
                    dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($client_phone, $whatsapp_message));
                }

                foreach ($order->subOrders as $provider){
                    $provider_phone = $provider->provider?->phone;
                    if($provider_phone)
                    {
                        $message_status = __('Order status has been changed from @old_status to @new_status. Order ID: @order_id');
                        $provider_whatsapp_message = str_replace(["@old_status","@new_status","@order_id"],[$old_status,$new_status,$order->id],$message_status);
                        dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($provider_phone, $provider_whatsapp_message));
                    }
                }
            }

        } catch (\Exception $e) { }


        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }

    public function changeAdminSubOrderStatus(Request $request){
        $sub_order_id = $request->id;
        $status = $request->status_id;

        $apply_cancellation_policy=$request->apply_cancellation_policy;
        // Fetch sub order with related models
        $sub_order = SubOrder::with('client', 'subOrderAddons', 'subOrderLocations', 'provider', 'staff')
            ->findOrFail($sub_order_id);

        $order = Order::find($sub_order->order_id);

        if (!$sub_order || !$order) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Order Not Found')));
        }

        $cancellation_policy=OrderCancellationPolicy::first();
        if(!$apply_cancellation_policy || $apply_cancellation_policy==0)
        {
            if(empty($cancellation_policy))
            {
                return redirect()->back()->with(FlashMsg::item_delete(__('Cancellation Policy Not Found')));
            }
        }


        // Status mapping
        $statusMapping = [
            0 => __('Pending'),
            1 => __('Active'),
            2 => __('Completed'),
            3 => __('Delivered'),
            4 => __('Canceled'),
            6 => __('refunded')
        ];

        $old_status = $statusMapping[$sub_order->status] ?? __('Unknown');
        $new_status = $statusMapping[$status] ?? __('Unknown');
        if(($sub_order->status == 4 && $status != 4))
        {
            SubOrder::where('id', $sub_order->id)->update(['is_refunded' => 0]);
            RefundedOrder::where('sub_order_id',$sub_order->id)->update(['status'=> 3]);
        }
        else if(($sub_order->status == 6 && $status != 6))
        {
            SubOrder::where('id', $sub_order->id)->update(['is_refunded' => 0]);
            RefundedOrder::where('sub_order_id',$sub_order->id)->update(['status'=> 3]);
        }


        if((int)$status== 4)//4==cancel
        {
             if($apply_cancellation_policy==0)
             {
                $result=$this->cancelOrderWithPolicy($sub_order);
             }
             else if($apply_cancellation_policy==1)
             {
                $result=$this->cancelOrder($sub_order);
             }


             $result=$result->getOriginalContent();

            if($result['success'] == false)
            {
                return redirect()->back()->with(FlashMsg::item_delete($result['message']));
            }

        }
        else if((int)$status == 2)//2==complete
        {
            if($sub_order->status != 2 && $sub_order->status != 4 && $sub_order->status != 6 )//not complete,not cancel
            {

                $this->providerEarningsService->updateProviderEarnings($sub_order);
            }
            $sub_order->update(['status' => (int)$status]);

        }
        else if((int)$status == 6)
        {
            $result=$this->refundOrder($sub_order);
            $result=$result->getOriginalContent();

            if($result['success'] == false)
            {
                return redirect()->back()->with(FlashMsg::item_delete($result['message']));
            }

        }
        else if($sub_order->status == 2 && ((int)$status == 0 || (int)$status == 1) )
        {
            $this->providerEarningsServiceUpdate->updateProviderEarnings($sub_order);
            $sub_order->update(['status' => (int)$status]);
        }
        else
        {
            // Update sub order status
            $sub_order->update(['status' => (int)$status]);
        }


        // Use the action to update the main order status
        $this->updateMainOrderStatus->execute($order, $sub_order);


        $client_email = optional($sub_order->client)->email;
        $client_phone= optional($sub_order->client)->phone;
        $client_firebase_token = optional($sub_order->client)->firebase_token;
        $provider_firebase_token = optional($sub_order->provider)->firebase_token;


            try {
                // Define the title and body based on the order status
                $statusMessages = [
                    0 => [
                        'title' => __('Order #:order_id Pending'),
                        'body' => __('Your order #:order_id (Sub-order #:sub_order_id) has been created and is now pending.')
                    ],
                    1 => [
                        'title' => __('Order #:order_id Active'),
                        'body' => __('Your order #:order_id (Sub-order #:sub_order_id) is now active.')
                    ],
                    2 => [
                        'title' => __('Order #:order_id Completed'),
                        'body' => __('Your order #:order_id (Sub-order #:sub_order_id) has been successfully completed.')
                    ],
                    3 => [
                        'title' => __('Order #:order_id Delivered'),
                        'body' => __('Your order #:order_id (Sub-order #:sub_order_id) has been delivered.')
                    ],
                    4 => [
                        'title' => __('Order #:order_id Cancelled'),
                        'body' => __('Your order #:order_id (Sub-order #:sub_order_id) has been cancelled.')
                    ],

                ];


                if (!empty($client_firebase_token)) {
                    // Get the appropriate title and body based on the current status of the sub_order
                    $title = $statusMessages[$sub_order->status]['title'] ?? __('Order Status Changed');
                    $body = $statusMessages[$sub_order->status]['body'] ?? __('The status of your order has been updated.');

                    // Replace placeholders with actual order ID and sub-order ID
                    $title = str_replace([':order_id'], [$sub_order->order_id], $title);
                    $body = str_replace([':order_id', ':sub_order_id'], [$sub_order->order_id, $sub_order->id], $body);

                    if (!empty($sub_order->client)) {
                        user_notification($sub_order->order_id, $sub_order->client?->id, 'order', $body, 'unread');
                    }

                    $client_notification_data = [
                        "title" => $title,
                        "detailed_title" => "-",
                        "identify" => $sub_order->order_id, // identify
                        "sub_order_id" => $sub_order->id ?? 0, // identify
                        "user_id" => $sub_order->client?->id ?? 0, // user id
                        "body" => $body,
                        "description" => "-",
                        "type" => "order",
                        "sound" => "default",
                        "screen" => "-",
                    ];

                    // Pass the token as an array
                    $this->orderServiceNotification->sendFirebaseNotification([$client_firebase_token], $title, $body, $client_notification_data);

                }

                if (!empty($provider_firebase_token)) {
                    // Get the appropriate title and body based on the current status of the sub_order
                    $provider_title = $statusMessages[$sub_order->status]['title'] ?? __('Order Status Changed');

                    // Set the provider title without the sub-order ID
                    $provider_title = str_replace([':order_id'], [$sub_order->id], $provider_title);

                    // Set the body to the desired format: "Your order #242 is now active."
                    $provider_body = __('Your order #:order_id is now :new_status.'); // Adjust this line as needed

                    // Replace placeholders with actual order ID and status
                    $provider_body = str_replace(
                        [':order_id', ':new_status'],
                        [$sub_order->id, $new_status],
                        $provider_body
                    );

                    if (!empty($sub_order->provider)) {
                        user_notification($sub_order->id, $sub_order->provider?->id, 'order', $provider_body, 'unread');
                        $provider_notification_data = [
                            "title" => $provider_title,
                            "detailed_title" => "-",
                            "identify" => $sub_order->id, // identify
                            "sub_order_id" => $sub_order->id ?? 0, // sub-order id
                            "user_id" => $sub_order->provider?->id ?? 0, // provider id
                            "body" => $provider_body,
                            "description" => "-",
                            "type" => "order",
                            "sound" => "default",
                            "screen" => "-"
                        ];
                    }

                    if (!is_null($provider_firebase_token)) {
                        $this->orderServiceNotification->sendFirebaseNotification([$provider_firebase_token], $provider_title, $provider_body, $provider_notification_data);
                    }

                }

            }catch (\Exception $e) {}



        // If client email exists, send email
        try {
            if ($client_email) {
                Mail::to($client_email)->send(new BasicMail([
                    'subject' => $title,
                    'message' => $body,
                ]));
            }
            // For provider
            $provider_email = optional($sub_order->provider)->email;
            if (!empty($provider_email)) {
                Mail::to($provider_email)->send(new BasicMail([
                    'subject' => $provider_title,
                    'message' => $provider_body,
                ]));
            }


        } catch (\Exception $e) {
        }

        try{
            if(moduleExists('WhatsAppBookingSystem'))
            {

                //send whatsapp message
                if($client_phone)
                {

                    $whatsapp_message = str_replace([':order_id', ':new_status'], [$sub_order->id, $new_status], __('Your order #:order_id is now :new_status.'));
                    dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($client_phone, $whatsapp_message));
                }

                $provider_phone = optional($sub_order->provider)->phone;
                if (!empty($provider_phone)) {
                    $provider_whatsapp_message = str_replace([':order_id', ':new_status'], [$sub_order->id, $new_status], __('Order #:order_id is now :new_status.'));
                    dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($provider_phone, $provider_whatsapp_message));
                }
            }
        }catch(\Exception $e) {
            // Handle exception if needed
        }

        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }


    public function refundOrder($suborder)
    {

       $old_status=$suborder->status;

        if(($old_status==2 || $old_status == 3 ) && $suborder->payment_status=="complete")
        {
            if($suborder->provider_id)
            {
                $complete_order_balance_with_tax = $suborder->total; // Total amount including tax
                $complete_order_tax = $suborder->tax;  // Tax amount
                $admin_commission_amount = $suborder->commission_amount;   // Admin commission amount

                // Calculate order balance without tax
                $complete_order_balance_without_tax = $complete_order_balance_with_tax - $complete_order_tax;
                // Calculate provider's earning amount
                $refunded_amount  = $complete_order_balance_without_tax - $admin_commission_amount;



                $refunded_order=RefundedOrder::updateOrCreate(
                    [
                        'order_id'=>$suborder->order_id,
                        'sub_order_id'=>$suborder->id,
                        'client_id'=>$suborder->client_id,
                        'provider_id'=>$suborder->provider_id,
                    ],[

                    'amount'=>$refunded_amount,
                    'cancel_reason'=>"",
                    'user_type'=> 1,
                    'current_suborder_status' => $old_status,
                    'type'=> 0,
                    'status'=> 1,
                    'add_fee' => 0
                ]);

                $suborder->update([
                    'status'=>6,//6=refunded
                    'is_refunded' =>1
                ]);


            }
            else if($suborder->admin_id)
            {
                $complete_order_balance_with_tax = $suborder->total; // Total amount including tax
                $complete_order_tax =$suborder->tax;  // Tax amount

                // Calculate order balance without tax
                $refunded_amount= $complete_order_balance_with_tax - $complete_order_tax;

                $refunded_order=RefundedOrder::updateOrCreate([
                    'order_id'=>$suborder->order_id,
                    'sub_order_id'=>$suborder->id,
                    'client_id'=>$suborder->client_id,
                    'admin_id'=>$suborder->admin_id,

                    ],[

                    'amount'=>$refunded_amount,
                    'cancel_reason'=>"",
                    'status'=>1,
                    'user_type'=> 1,
                    'current_suborder_status' => $old_status,
                    'type'=> 0,
                    'status'=> 1,
                    'add_fee' => 0
                ]);
                $suborder->update([
                    'status'=>6,//6=refunded
                    'is_refunded' =>1
                ]);

            }

            $success =true;
            $message = __("Success");
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);

        }
        else
        {
            $success =false;
            $message = __("Sub order ".$suborder->id." not refundable");
            $result=[
                'success' => $success,
                'message' => $message
            ];
            return response()->json($result);
        }
    }

    public function cancelOrderWithPolicy($suborder){


        $refunded_amount=0;
        $order_id=$suborder->order_id;
        $order=Order::where('id',$order_id)->first();
        $old_status=$suborder->status;


        $time_expired="true";
        $message="";
        $success="";

        $cancellation_policy=OrderCancellationPolicy::first();

        $available_type=$cancellation_policy?->available_type;
        if($available_type=='certain_time')
        {
            $order=Order::where('id',$order_id)->first();
            $cancel_time=$cancellation_policy?->time_in_min;
            $current_time=Carbon::now();
            $order_created_time=Carbon::parse($order->created_at);
            $diff_in_minutes=$current_time->diffInMinutes($order_created_time);
            if($diff_in_minutes<=$cancel_time)
            {
                $time_expired="false";

            }
            else
            {
                $success =false;
                $message = __("You can cancel the order within ".$cancel_time." minutes");
                $result=[
                    'success' => $success,
                    'message' => $message
                ];
                return response()->json($result);

            }
        }


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
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==1))
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

            if($available_type=='certain_time')
            {
                if($time_expired =="false")
                {
                    if( $cancellation_policy?->fine_type =="flat")
                    {
                        $refunded_amount=$suborder->total - $cancellation_policy->amount;
                    }
                    else if( $cancellation_policy?->fine_type =="percentage")
                    {
                        $refunded_amount=$suborder->total - ($suborder->total * $cancellation_policy?->amount/100);
                    }
                    $suborder->status=4;
                    $suborder->is_refunded=1;
                    $suborder->save();

                    $refunded_order=RefundedOrder::updateOrCreate(
                        [
                            'order_id'=>$suborder->order_id,
                            'sub_order_id'=>$suborder->id,
                            'client_id'=>$suborder->client_id,
                            'provider_id'=>$suborder->provider_id,
                            'admin_id'=>$suborder->admin_id,
                        ],[
                        'amount'=>$refunded_amount,
                        'status'=>1,
                        'user_type'=>2,
                        'current_suborder_status'=>$old_status,
                        'type'=> 0,
                        'status'=> 0,
                        'add_fee' => 1
                    ]);
                    $messgae="Order Cancel successfully";
                    $success="true";
                    $result=[
                        'success' => $success,
                        'message' => $messgae
                    ];
                    return response()->json($result);

                }
            }
            else
            {

                if( $cancellation_policy?->fine_type =="flat")
                {
                    $refunded_amount=$suborder->total - $cancellation_policy?->amount;
                }
                else if( $cancellation_policy?->fine_type =="percentage")
                {
                    $refunded_amount=$suborder->total - ($suborder->total * $cancellation_policy?->amount/100);
                }
                $suborder->status=4;
                $suborder->is_refunded=1;
                $suborder->save();

                $refunded_order=RefundedOrder::updateOrCreate(
                    [
                        'order_id'=>$suborder->order_id,
                        'sub_order_id'=>$suborder->id,
                        'client_id'=>$suborder->client_id,
                        'provider_id'=>$suborder->provider_id,
                        'admin_id'=>$suborder->admin_id,
                    ],[

                    'amount'=>$refunded_amount,
                    'status'=>1,
                    'user_type'=>2,
                    'current_suborder_status'=>$old_status,
                    'type'=> 0,
                    'status'=> 0,
                    'add_fee' => 1

                ]);
                $messgae="Order Cancel successfully";
                $success="true";
                $result=[
                    'success' => $success,
                    'message' => $messgae
                ];
                return response()->json($result);
            }
        }
        else if($suborder->payment_status!="complete")
        {
            if($available_type=='certain_time')
            {
                if($time_expired=="false")
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
            else
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
        else
        {
            if($available_type=='certain_time')
            {

                if($time_expired=="false")
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
            else
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






    }
    public function cancelOrder($suborder){


        $refunded_amount=0;
        $order_id=$suborder->order_id;
        $order=Order::where('id',$order_id)->first();
        $old_status=$suborder->status;


        $time_expired="true";
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
        else if(($suborder->payment_status=="complete" && $suborder->is_refunded==1))
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

            $refunded_order=RefundedOrder::updateOrCreate([
                'order_id'=>$suborder->order_id,
                'sub_order_id'=>$suborder->id,
                'client_id'=>$suborder->client_id,
                'provider_id'=>$suborder->provider_id,
                'admin_id'=>$suborder->admin_id,
                'amount'=>$refunded_amount,
                'status'=>1,
                'user_type'=>2,
                'current_suborder_status'=>$old_status,
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
        else if($suborder->payment_status!="complete")
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
        else
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




    public function change_payment_status($id){

        $order= Order::with('client','subOrders.provider')->where('id',$id)->first();

        if (!$order) {
            return redirect()->back()->with(FlashMsg::item_new('Order not found.'));
        }

        $old_status = $order->payment_status;

        if($order->payment_status=='pending'){
            $new_status = 'complete';
        }else{
            $new_status = 'pending';
        }

        Order::where('id',$id)->update([
            'payment_status'=> $new_status
        ]);

        SubOrder::where('order_id', $id)->update([
            'payment_status' => $new_status
        ]);

        try {
            // mail send to client
            $message = get_static_option('admin_change_payment_status_message') ?? __('Payment Status Changed.');
            $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$order->client?->fullname,$old_status,$new_status,$order->id],$message);
            Mail::to($order->client?->email)->send(new BasicMail([
                'subject' => get_static_option('admin_change_payment_status_subject') ?? __('Payment Status Changed.'),
                'message' => $message
            ]));

            // mail send to provider
            foreach ($order->subOrders as $subOrder) {
                $provider = $subOrder->provider;
                $message = get_static_option('admin_change_payment_status_message') ?? __('Payment Status Changed.');
                $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$provider->email,$old_status,$new_status,$order->id],$message);

                Mail::to( $provider->email)->send(new BasicMail([
                    'subject' => get_static_option('admin_change_payment_status_subject') ?? __('Payment Status Changed.'),
                    'message' => $message
                ]));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_new($e->getMessage()));
        }

        return redirect()->back()->with(FlashMsg::item_new('Status Change Success'));
    }



}
