<?php

namespace App\Http\Controllers\Api\Provider;

use App\Actions\Media\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderCompleteRequestResource;
use App\Http\Resources\Orders\OrderDetailsForProviderResource;
use App\Http\Resources\Orders\OrderListForProviderResource;
use App\Http\Resources\Orders\ProviderSubOrderListResource;
use App\Http\Resources\Reviews\ProviderReviewResource;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\UpdateMainOrderStatus;
use App\Jobs\SendOrderCreateEmail;
use App\Jobs\SendOrderStatusChangeEmail;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\OrderCompleteRequest;
use App\Models\Review;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Orders\RefundResource;
use App\Models\OrderCancellationPolicy;
use App\Models\RefundedOrder;
use Carbon\Carbon;

class ProviderOrderController extends Controller
{
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification, UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }

    public function todayOrderList(){

        $provider_id = Auth::guard('sanctum')->user()->id;

        $query =  SubOrder::with('order','client', 'service', 'subOrderAddons','subOrderLocations', 'staff')
            ->where('provider_id', $provider_id)
            ->whereDate('created_at', now()
            ->toDateString());

        $today_orders = $query->latest()->get();

        if ($today_orders->isNotEmpty()) {
            return response()->json([
                'today_orders' => ProviderSubOrderListResource::collection($today_orders),
            ]);
        }

        return response()->json([
            'message' => __('Order not yet'),
        ]);
    }

    public function myOrders(Request $request)
    {

        $status = $request->input('status');
        $provider_id = Auth::guard('sanctum')->user()->id;

        // Start building the query
        $query = SubOrder::with('order','client', 'service','job', 'subOrderAddons','subOrderLocations', 'staff', 'reviews')
            ->where('provider_id', $provider_id);

        if (!empty($status)){
            $query->where('status', $status);
        }


        $all_orders = $query->latest()->paginate(10);

        if ($all_orders->isNotEmpty()) {
            return response()->json([
                'all_orders' => ProviderSubOrderListResource::collection($all_orders->items()),
                'pagination' => [
                    'total' => $all_orders->total(),
                    'count' => $all_orders->count(),
                    'per_page' => $all_orders->perPage(),
                    'current_page' => $all_orders->currentPage(),
                    'last_page' => $all_orders->lastPage(),
                    'next_page_url' => $all_orders->nextPageUrl(),
                    'prev_page_url' => $all_orders->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Order not yet available'),
        ]);
    }
    public function orderDetails($sub_order_id){
        if(empty($sub_order_id)){
            return response()->json(['message' => __('no order found')]);
        }

        $provider_id = Auth::guard('sanctum')->user()->id;

        $order_details = SubOrder::with('order','client', 'service', 'job','subOrderAddons','subOrderLocations', 'staff', 'reviews', 'order_complete_request')
            ->where('id', $sub_order_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (!empty($order_details)) {
            return response()->json([
                'order_details' =>new OrderDetailsForProviderResource($order_details),
            ]);
        }

        return response()->json([
            'message' => __('Order not yet.'),
        ], 404);
    }

    public function orderStatus(Request $request)
    {
        if($request->status == '' || $request->order_id == '' || $request->sub_order_id == ''){
            return response()->json([
                'msg' => __('Please select both status, order and suborder id first.'),
            ], 400);
        }

        $provider_id = Auth::guard('sanctum')->user()->id;

        // first check if already request created
        $order_completed_request_check = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('provider_id', $provider_id)
            ->latest()
            ->first();

        if (!empty($order_completed_request_check)){
            if ($order_completed_request_check->status == 1) {
                return response()->json([
                    'msg' => __('Order already completed.'),
                ]);
            }

            if ($order_completed_request_check->status == 0) {
                return response()->json([
                    'msg' => __('order complete request already been created and is pending..'),
                ]);
            }
        }


        $order = Order::with('client')->where('id',$request->order_id)->first();
        $sub_order = SubOrder::where('id',$request->sub_order_id)->first();

        if (empty($sub_order) || empty($order)){
            return response()->json([
                'msg' => __('Order Not Found'),
            ], 404);
        }

        // if cancel order
        if($sub_order->is_refunded === 1){
            return response()->json([
                'msg' => __('You can not change status because earlier you canceled the order'),
            ], 400);
        }


        // if order status not 2 and order payment status is complete
        if(($sub_order->status != 2 && $order->payment_status == 'complete')
            || ($sub_order->status != 2 && $order->payment_gateway == 'cash_on_delivery')){
                if($request->status == 2){
                    // update sub order
                    $sub_order->update([
                        'complete_request' => 1,
                        'payment_status' => 'complete',
                    ]);

                     $last_image_id = null;
                    if($request->file('file')){
                        MediaHelper::insert_media_image($request,'web');
                        $last_image_id = DB::getPdo()->lastInsertId();
                    }

                    OrderCompleteRequest::create([
                        'order_id' => $sub_order->order_id,
                        'sub_order_id' => $sub_order->id,
                        'client_id' => $sub_order->client_id,
                        'provider_id' => $sub_order->provider_id,
                        'message'=> __('Not decline or complete yet. Please wait'),
                        'image' => $last_image_id,
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

                    return response()->json([
                        'msg' => __('Your request submitted. Client will complete your request after review.'),
                    ]);

                }
        }else{
            return response()->json([
                'msg' => __('You can not change order status because this order already completed & order status due to payment status pending.'),
            ], 400);
        }
    }

    public function orderCompleteRequestHistory(Request $request)
    {
        $provider_id = Auth::guard('sanctum')->user()->id;

         $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'sub_order_id' => 'required|integer|exists:sub_orders,id',
        ]);

        $order_complete_request = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('provider_id', $provider_id)
            ->latest()
            ->get();

        if(!empty($order_complete_request)){
            return response()->json([
                'order_complete_request'=> OrderCompleteRequestResource::collection($order_complete_request),
            ]);
        }else{
            return response()->json([
                'msg'=>__('Order complete request not found.'),
            ]);
        }
    }

    public function codPaymentStatusChange(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
        ]);

        $provider_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        $sub_order = SubOrder::where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (empty($sub_order) || empty($order)) {
            return response()->json([
                'success' => false,
                'msg' => __('Order not found.'),
            ]);
        }

        // if payment gateway cash on delivery
        if($order->payment_gateway === "cash_on_delivery"){
            $sub_order->payment_status = "complete";
            $sub_order->save();

            return response()->json([
                'msg'=> __('payment status update success'),
            ]);

        }else{
            return response()->json([
                'msg'=> __('Order payment status not cash_on_delivery'),
            ]);
        }

        return response()->json([
            'msg'=> __('something went wrong, try after sometime')
        ],500);
    }

    public function OrderStatusChangeCancel(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
        ]);

        $provider_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        if (empty($order)) {
            return response()->json([
                'msg' => __('Order not found.'),
            ], 404);
        }

        $sub_order = SubOrder::with('client','provider')
            ->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('provider_id', $provider_id)
            ->whereIn('status', [1, 3])
            ->first();

        if (empty($sub_order) || empty($order)) {
            return response()->json([
                'msg' => __('Order not found.'),
            ], 404);
        }

        if(!empty($sub_order)){
            // update sub order status
            $result=$this->cancelOrder($sub_order);
             $result=$result->getOriginalContent();

            if($result['success'] == false)
            {
                return response()->json([
                    'msg' => $result['message'],
                ], 400);
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
                if(moduleExists('WhatsAppBookingSystem'))
                {
                    $client_phone= optional($sub_order->client)->phone;
                    $new_status= __('canceled');
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

            }catch (\Exception $e) {}

            return response()->json([
                'msg'=> __('order status changed to cancel'),
            ]);
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

    public function orderAcceptDecline(Request $request)
    {
       // return "ok";
        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
        ]);

        $status =(int)$request->input('status');

        $provider_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        if (empty($order)) {
            return response()->json([
                'msg' => __('Order not found.'),
            ], 404);
        }

        $sub_order = SubOrder::with('client','provider')
            ->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('provider_id', $provider_id)
            ->whereIn('status', [0, 1]) // pending or active
            ->first();

        if (empty($sub_order) || empty($order)) {
            return response()->json([
                'msg' => __('Order not found.'),
            ], 404);
        }

        if(!empty($sub_order)){
            // update sub order status
            if($status == 5)
            {

                $result=$this->declineOrder($sub_order);

                $result=$result->getOriginalContent();

               if($result['success'] == false)
               {
                    return response()->json([
                        'msg' => $result['message'],
                    ], 400);
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
                return response()->json([
                    'msg'=> __('order accepted success'),
                ]);
            }elseif($status == 5){
                return response()->json([
                    'msg'=> __('order decline success'),
                ]);
            }else{
                return response()->json([
                    'msg'=> __('order status changed.'),
                ]);
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

    public function allProviderReviews(){

       $provider_id = Auth::guard('sanctum')->user()->id;

        $provider_all_reviews = Review::with('reviewer', 'service')->where('user_id', $provider_id)->where('user_type','0')->latest()->paginate(10);
        $provider_rating = Review::where('user_id', $provider_id)->where('user_type','0')->avg('rating');
        $provider_rating_percentage_value = round($provider_rating * 20);

        if ($provider_all_reviews->count() > 0){
            return response()->json([
                'provider_rating' => $provider_rating_percentage_value,
                'provider_all_reviews' => $provider_all_reviews ? ProviderReviewResource::collection($provider_all_reviews) : null,
                'pagination' => [
                    'total' => $provider_all_reviews->total(),
                    'count' => $provider_all_reviews->count(),
                    'per_page' => $provider_all_reviews->perPage(),
                    'current_page' => $provider_all_reviews->currentPage(),
                    'last_page' => $provider_all_reviews->lastPage(),
                    'next_page_url' => $provider_all_reviews->nextPageUrl(),
                    'prev_page_url' => $provider_all_reviews->previousPageUrl(),
                ]
            ]);
        }else{
            return response()->json([
                'message' => __('Review Not Found.')
            ]);
        }

    }

    public function refundList()
    {
        $provider_id = Auth::guard('sanctum')->user()->id;

        $refunded_orders = RefundedOrder::with('user','provider','admin','order')
            ->where('provider_id', $provider_id)
            ->latest()
            ->paginate(10);

        if ($refunded_orders->count() > 0){
            return response()->json([
                'refunded_orders' => $refunded_orders ? RefundResource::collection($refunded_orders) : null,
                'pagination' => [
                    'total' => $refunded_orders->total(),
                    'count' => $refunded_orders->count(),
                    'per_page' => $refunded_orders->perPage(),
                    'current_page' => $refunded_orders->currentPage(),
                    'last_page' => $refunded_orders->lastPage(),
                    'next_page_url' => $refunded_orders->nextPageUrl(),
                    'prev_page_url' => $refunded_orders->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Refund Not Found.')
        ]);
    }

    //accept or decline refund request
    public function acceptRefundRequest(Request $request)
    {
        $request->validate([
            'refund_request_id' => 'required|integer|exists:refunded_orders,id',
            'status' => 'required|in:1', // 1=approve, 3=cancel
        ]);

        $provider_id = Auth::guard('sanctum')->user()->id;

        $refunded_order = RefundedOrder::where('id', $request->refund_request_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (empty($refunded_order)) {
            return response()->json([
                'msg' => __('Refund request not found.'),
            ], 404);
        }

        // Update the status of the refund request
        $refunded_order->status = (int) $request->status;
        $refunded_order->save();

        if($refunded_order->status == 1){

            //make suborder status to cancel

            $sub_order = SubOrder::where('id', $refunded_order->sub_order_id)
                ->where('order_id', $refunded_order->order_id)
                ->first();
            $order=Order::where("id",$refunded_order->order_id)->first();
            if (!empty($sub_order)) {
                $sub_order->status = 6; // 6=refunded
                $sub_order->is_refunded = 1;
                $sub_order->save();
                // update main order status
                $this->updateMainOrderStatus->execute($order, $sub_order);

                try{
                    //send notification
                    $this->orderServiceNotification->refundAcceptNotification($sub_order,$refunded_order);
                }catch(\Exception $e)
                {

                }
            }

        }


        return response()->json([
            'msg' => __('Refund request status updated successfully.'),
        ]);
    }

    public function declineRefundRequest(Request $request)
    {
        $request->validate([
            'refund_request_id' => 'required|integer|exists:refunded_orders,id',
            'status' => 'required|in:3', // 1=approve, 3=cancel
        ]);

        $provider_id = Auth::guard('sanctum')->user()->id;

        $refunded_order = RefundedOrder::where('id', $request->refund_request_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (empty($refunded_order)) {
            return response()->json([
                'msg' => __('Refund request not found.'),
            ], 404);
        }

        // Update the status of the refund request
        $refunded_order->status = (int) $request->status;
        $refunded_order->save();


        if($refunded_order->status == 3){

            $sub_order = SubOrder::where('id', $refunded_order->sub_order_id)
                ->where('order_id', $refunded_order->order_id)
                ->first();
            $order=Order::where("id",$refunded_order->order_id)->first();
            if (!empty($sub_order)) {
                $sub_order->status = 0; // 1=active
                $sub_order->is_refunded = 0;
                $sub_order->save();
                // update main order status
                $this->updateMainOrderStatus->execute($order, $sub_order);
                try{
                    //send notification
                    $this->orderServiceNotification->refundDeclineNotification($sub_order,$refunded_order);
                }catch(\Exception $e)
                {

                }
            }

        }

        return response()->json([
            'msg' => __('Refund request status updated successfully.'),
        ]);
    }

    //redund request complete
    // public function refundRequestComplete(Request $request)
    // {
    //     $request->validate([
    //         'refund_request_id' => 'required|integer|exists:refunded_orders,id',
    //     ]);

    //     $provider_id = Auth::guard('sanctum')->user()->id;

    //     $refunded_order = RefundedOrder::where('id', $request->refund_request_id)
    //         ->where('provider_id', $provider_id)
    //         ->first();

    //     if (empty($refunded_order)) {
    //         return response()->json([
    //             'msg' => __('Refund request not found.'),
    //         ], 404);
    //     }

    //     // Update the status of the refund request
    //     $refunded_order->status = 2; // 2=complete
    //     $refunded_order->save();

    //     return response()->json([
    //         'msg' => __('Refund request completed successfully.'),
    //     ]);
    // }
    public function refundDetails($refund_request_id)
    {
        $provider_id = Auth::guard('sanctum')->user()->id;

        $refunded_order = RefundedOrder::with('user','provider','admin','order')
            ->where('id', $refund_request_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (empty($refunded_order)) {
            return response()->json([
                'msg' => __('Refund request not found.'),
            ], 404);
        }

        return response()->json([
            'refunded_order' => $refunded_order,
        ]);
    }



    // public function cancelOrder($suborder){


    //     $refunded_amount=0;
    //     $order_id=$suborder->order_id;
    //     $order=Order::where('id',$order_id)->first();


    //     $items=[];
    //     $time_expired="true";
    //     $message="";
    //     $success="";

    //     $cancellation_policy=OrderCancellationPolicy::first();
    //     $available_type=$cancellation_policy->available_type;
    //     if($available_type=='certain_time')
    //     {
    //         $order=Order::where('id',$order_id)->first();
    //         $cancel_time=$cancellation_policy->time_in_min;
    //         $current_time=Carbon::now();
    //         $order_created_time=Carbon::parse($order->created_at);
    //         $diff_in_minutes=$current_time->diffInMinutes($order_created_time);
    //         if($diff_in_minutes<=$cancel_time)
    //         {
    //             $time_expired="false";

    //         }
    //         else
    //         {
    //             $success =false;
    //             $message = __("You can cancel the order within ".$cancel_time." minutes");
    //             $result=[
    //                 'success' => $success,
    //                 'message' => $message
    //             ];
    //             return response()->json($result);

    //         }
    //     }


    //     if($suborder->status== 4)
    //     {

    //         $success =false;
    //         $message =__("Order for this ".$suborder->service?->title." already canceled");
    //         $result=[
    //             'success' => $success,
    //             'message' => $message
    //         ];
    //         return response()->json($result);
    //     }
    //     else if($order->payment_status=="complete" )
    //     {

    //         if($available_type=='certain_time')
    //         {
    //             if($time_expired =="false")
    //             {
    //                 if( $cancellation_policy->fine_type =="flat")
    //                 {
    //                     $refunded_amount=$suborder->total - $cancellation_policy->amount;
    //                 }
    //                 else if( $cancellation_policy->fine_type =="percentage")
    //                 {
    //                     $refunded_amount=$suborder->total - ($suborder->total * $cancellation_policy->amount/100);
    //                 }
    //                 $suborder->status=4;
    //                 $suborder->is_refunded=1;
    //                 $suborder->save();

    //                 $refunded_order=RefundedOrder::create([
    //                     'order_id'=>$suborder->order_id,
    //                     'sub_order_id'=>$suborder->id,
    //                     'client_id'=>$suborder->client_id,
    //                     'provider_id'=>$suborder->provider_id,
    //                     'amount'=>$refunded_amount,
    //                 ]);
    //                 $messgae="Order Cancel successfully";
    //                 $success="true";
    //                 $result=[
    //                     'success' => $success,
    //                     'message' => $messgae
    //                 ];
    //                 return response()->json($result);

    //             }
    //         }
    //         else
    //         {

    //             if( $cancellation_policy->fine_type =="flat")
    //             {
    //                 $refunded_amount=$suborder->total - $cancellation_policy->amount;
    //             }
    //             else if( $cancellation_policy->fine_type =="percentage")
    //             {
    //                 $refunded_amount=$suborder->total - ($suborder->total * $cancellation_policy->amount/100);
    //             }
    //             $suborder->status=4;
    //             $suborder->is_refunded=1;
    //             $suborder->save();

    //             $refunded_order=RefundedOrder::create([
    //                 'order_id'=>$suborder->order_id,
    //                 'sub_order_id'=>$suborder->id,
    //                 'client_id'=>$suborder->client_id,
    //                 'provider_id'=>$suborder->provider_id,
    //                 'amount'=>$refunded_amount,

    //             ]);
    //             $messgae="Order Cancel successfully";
    //             $success="true";
    //             $result=[
    //                 'success' => $success,
    //                 'message' => $messgae
    //             ];
    //             return response()->json($result);
    //         }
    //     }
    //     else if($order->payment_status!="complete")
    //     {
    //         if($available_type=='certain_time')
    //         {

    //             if($time_expired=="false")
    //             {
    //                 $suborder->status=4;
    //                 $suborder->is_refunded=0;
    //                 $suborder->save();

    //                 $message="Order Cancel successfully";
    //                 $success="true";
    //                  $result=[
    //                     'success' => $success,
    //                     'message' => $message
    //                 ];
    //                 return response()->json($result);

    //             }


    //         }
    //         else
    //         {
    //             $suborder->status=4;
    //             $suborder->is_refunded=0;
    //             $suborder->save();

    //             $message="Order Cancel successfully";
    //             $success="true";
    //             $result=[
    //                 'success' => $success,
    //                 'message' => $message
    //             ];
    //             return response()->json($result);

    //         }


    //     }






    // }




}
