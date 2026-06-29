<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderCompleteRequestResource;
use App\Http\Resources\Orders\OrderDetailsResource;
use App\Http\Resources\Orders\OrderListForClientResource;
use App\Http\Resources\Reviews\ReviewResource;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\ProviderEarningsService;
use App\Jobs\SendOrderStatusChangeEmail;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\OrderCompleteRequest;
use App\Models\Review;
use App\Models\SubOrder;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Orders\RefundResource;
use App\Http\Resources\Orders\RefundDetailsForClientResource;
use App\Http\Resources\Orders\RefundRequestResource;
use App\Jobs\SendRedundRequestEmail;
use App\Models\RefundedOrder;
use App\Http\Services\UpdateMainOrderStatus;


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

    public function clientAllOrders(Request $request)
    {
        $status = $request->input('status');
        $order_type = $request->input('order_type');
        $client_id = Auth::guard('sanctum')->user()->id;
        $query = Order::with('subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service', 'subOrders.job')
                        ->where('user_id', $client_id);

        if ($status !== null) {
            $query->where('status', intval($status));
        }

        // Filter orders based on order type (service or job)
        if ($order_type === 'service') {
            $query->whereHas('subOrders', function ($q) {
                $q->whereNotNull('service_id');
            });
        } elseif ($order_type === 'job') {
            $query->whereHas('subOrders', function ($q) {
                $q->whereNotNull('job_post_id');
            });
        }

        $all_orders = $query->latest()->paginate(10);

        if ($all_orders->isNotEmpty()) {
            return response()->json([
                'all_services' => OrderListForClientResource::collection($all_orders->items()),
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
            'message' => __('Order not yet'),
        ],404);

    }

    public function clientOrderDetails($id=null)
    {
        $client_id = Auth::guard('sanctum')->user()->id;
        $order_details = Order::with('client', 'subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.provider', 'subOrders.staff', 'subOrders.service', 'subOrders.job', 'subOrders.reviews', 'subOrders.order_complete_request')
            ->where('id', $id)
            ->where('user_id', $client_id)
            ->first();

        if ($order_details) {
            return response()->json([
                'order_details' => new OrderDetailsResource($order_details),
            ]);
        }

        return response()->json([
            'message' => __('Order not yet'),
        ], 404);

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
                'msg' => __('Order not found.'),
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
                'msg' => __('Order Complete Request Not Found.'),
            ], 404);
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
                'msg'=>__('Sub-Order complete request successfully approved.'),
            ]);

        }else{
            return response()->json([
                'msg'=>__('Order id does not exists.'),
            ], 404);
        }
    }

    public function orderCompleteRequestDecline(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'sub_order_id' => 'required',
            'decline_reason' => 'required|min:10|max:1000',
        ]);

        $client_id = Auth::guard('sanctum')->user()->id;
        $order = Order::find($request->order_id);

        $sub_order = SubOrder::with('provider')->where('id', $request->sub_order_id)
            ->where('order_id', $request->order_id)
            ->where('client_id', $client_id)
            ->first();

        if (empty($sub_order) || empty($order)) {
            return response()->json([
                'success' => false,
                'msg' => __('Order not found.'),
            ], 404);
        }

        // order request complete find
        $order_complete_request = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('client_id', $client_id)
            ->latest()
            ->first();

        if (empty($order_complete_request)) {
            return response()->json([
                'success' => false,
                'msg' => __('Order Complete Request Not Found.'),
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
                'msg'=>__('Sub-Order complete request decline.'),
            ]);

        }else{
            return response()->json([
                'msg'=>__('Order id does not exists.'),
            ]);
        }
    }

    public function orderCompleteRequestHistory(Request $request)
    {
        $client_id = Auth::guard('sanctum')->user()->id;
        $histories = OrderCompleteRequest::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('client_id', $client_id)
            ->latest()
            ->paginate(10);

        if ($histories->isNotEmpty()) {
            return response()->json([
                'order_complete_request'=> OrderCompleteRequestResource::collection($histories),
                'pagination' => [
                    'total' => $histories->total(),
                    'count' => $histories->count(),
                    'per_page' => $histories->perPage(),
                    'current_page' => $histories->currentPage(),
                    'last_page' => $histories->lastPage(),
                    'next_page_url' => $histories->nextPageUrl(),
                    'prev_page_url' => $histories->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'msg'=>__('Order id does not exists.'),
        ]);

    }

    public function allClientReviews(){

        $client_id = Auth::guard('sanctum')->user()->id;

        $client_all_reviews = Review::with('reviewer')->where('user_id', $client_id)
            ->where("user_type",'1')
            ->latest()
            ->paginate(10);

        if ($client_all_reviews->count() > 0){
            return response()->json([
                'client_all_reviews' => $client_all_reviews ? ReviewResource::collection($client_all_reviews) : null,
                'pagination' => [
                    'total' => $client_all_reviews->total(),
                    'count' => $client_all_reviews->count(),
                    'per_page' => $client_all_reviews->perPage(),
                    'current_page' => $client_all_reviews->currentPage(),
                    'last_page' => $client_all_reviews->lastPage(),
                    'next_page_url' => $client_all_reviews->nextPageUrl(),
                    'prev_page_url' => $client_all_reviews->previousPageUrl(),
                ]
            ]);
        }else{
            return response()->json([
                'message' => __('Review Not Found.')
            ]);
        }

    }

    public function refundRequestSend(Request $request)
    {
          // Ensure the user is authenticated
          if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => __("Unauthorized access")
            ], 401);
        }
        $user=Auth::guard('sanctum')->user();
        $user_id=$user->id;
        $order_id=$request->order_id;
        $sub_order_id=json_decode($request->sub_order_ids,true);
        $cancel_reason=$request->cancel_reason;
        $gateway_id=$request->gateway_id;
        $gateway_fields=$request->gateway_fields;
        $refunded_amount=0;
        if(!$order_id)
        {
            return response()->json([
               'success' => false,
               'message' => __("Order id required")
            ], 400);
        }
        if(empty($sub_order_id))
        {
            return response()->json([
                'success' => false,
                'message' => __("Sub order id required")
            ], 400);
        }

        $sub_order_ids=[];
        $items=[];
        foreach($sub_order_id as $key=>$value)
        {
            $sub_order_ids[]=$value;
        }

        $sub_orders=SubOrder::whereIn('id',$sub_order_ids)->where("client_id",$user_id)->get();

        $order=Order::where('id',$order_id)->first();
        if(empty($order))
        {
            return response()->json([
                'success' => false,
                'message' => __("Order not found")
            ], 404);
        }
        if($sub_orders->isEmpty())
        {
            return response()->json([
                'success' => false,
                'message' => __("Sub order not found")
            ], 404);
        }

        $refunded_order=[];

        //if sub order status and payment status check wheteher complete or not
        foreach($sub_orders as $key=>$value)
        {
            $old_status=$value->status;
            if(($value->status==2 || $value->status== 3 ) && $order->payment_status=="complete")
            {
                if($value->provider_id)
                {
                    $complete_order_balance_with_tax = $value->total; // Total amount including tax
                    $complete_order_tax = $value->tax;  // Tax amount
                    $admin_commission_amount = $value->commission_amount;   // Admin commission amount

                    // Calculate order balance without tax
                    $complete_order_balance_without_tax = $complete_order_balance_with_tax - $complete_order_tax;
                    // Calculate provider's earning amount
                    $refunded_amount  = $complete_order_balance_without_tax - $admin_commission_amount;



                    $refunded_order[]=RefundedOrder::updateOrCreate(
                        [
                            'order_id'=>$value->order_id,
                            'sub_order_id'=>$value->id,
                            'client_id'=>$value->client_id,
                            'provider_id'=>$value->provider_id,
                        ],[

                        'amount'=>$refunded_amount,
                        'gateway_id'=>$gateway_id,
                        'gateway_fields'=>$gateway_fields,
                        'cancel_reason'=>$cancel_reason ?? "",
                        'user_type'=> 1,
                        'current_suborder_status' => $old_status,
                        'type'=> 0,
                        'status'=> 0,
                        'add_fee' => 0
                    ]);


                }
                else if($value->admin_id)
                {
                    $complete_order_balance_with_tax = $value->total; // Total amount including tax
                    $complete_order_tax = $value->tax;  // Tax amount

                    // Calculate order balance without tax
                    $refunded_amount= $complete_order_balance_with_tax - $complete_order_tax;

                    $refunded_order[]=RefundedOrder::updateOrCreate([
                        'order_id'=>$value->order_id,
                        'sub_order_id'=>$value->id,
                        'client_id'=>$value->client_id,
                        'admin_id'=>$value->admin_id,

                        ],[

                        'amount'=>$refunded_amount,
                        'gateway_id'=>$gateway_id,
                        'gateway_fields'=>$gateway_fields,
                        'cancel_reason'=>$cancel_reason ?? "",
                        'status'=>1,
                        'user_type'=> 1,
                        'current_suborder_status' => $old_status,
                        'type'=> 0,
                        'add_fee' => 0
                    ]);
                    $value->update([
                        'status'=>6,//6=refunded
                        'is_refunded' =>1
                    ]);
                    // update main order status
                    $this->updateMainOrderStatus->execute($order, $value);

                }



            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => __("Sub order ".$value->id." not refundable")
                ], 400);
            }


        }
        if(!empty($refunded_order))
        {
            try {

                // Cancel order notifications
                $this->orderServiceNotification->cancelOrderNotification($order_id,$sub_order_ids,$request);
                // Dispatch job to send email in the background
                $order_details = Order::with('user','subOrders')->find($order_id);
                dispatch(new SendRedundRequestEmail($order_details,$sub_order_ids));
            }catch (\Exception $exception){


            }

            return response()->json([
                'success' => true,
                'message' => __("Refund request send successfully")
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => __("Refund request not send")
            ], 400);
        }

    }

    public function refundRequestHistory(Request $request)
    {
        $client_id = Auth::guard('sanctum')->user()->id;

        $histories = RefundedOrder::where('order_id', $request->order_id)
            ->where('sub_order_id', $request->sub_order_id)
            ->where('client_id', $client_id)
            ->latest()
            ->paginate(10);

        if ($histories->isNotEmpty()) {
            return response()->json([
                'refund_request_list'=> RefundRequestResource::collection($histories),
                'pagination' => [
                    'total' => $histories->total(),
                    'count' => $histories->count(),
                    'per_page' => $histories->perPage(),
                    'current_page' => $histories->currentPage(),
                    'last_page' => $histories->lastPage(),
                    'next_page_url' => $histories->nextPageUrl(),
                    'prev_page_url' => $histories->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'msg'=>__('Order id does not exists.'),
        ]);

    }

    public function refundList()
    {
        $client_id = Auth::guard('sanctum')->user()->id;

        $refunded_orders = RefundedOrder::with('user','provider','admin','order')
            ->where('client_id', $client_id)
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

    public function refundedOrderDetails($id=null)
    {
        $client_id = Auth::guard('sanctum')->user()->id;
        $refunded_order_details = RefundedOrder::with('user','provider','admin','order')
            ->where('id', $id)
            ->where('client_id', $client_id)
            ->first();

        if ($refunded_order_details) {
            return response()->json([
                'refunded_order_details' => new RefundDetailsForClientResource($refunded_order_details),
            ]);
        }

        return response()->json([
            'message' => __('Refunded not yet'),
        ], 404);

    }


}
