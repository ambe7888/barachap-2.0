<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RefundStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FlashMsg;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\UpdateMainOrderStatus;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\OrderCancellationPolicy;
use App\Models\RefundedOrder;
use App\Models\RefundGateway;
use Illuminate\Support\Facades\Mail;
use Modules\SMSGateway\app\Http\Traits\OtpGlobalTrait;
use Modules\SMSGateway\app\Models\SmsGateway;
use App\Models\UserBalance;
use App\Models\SubOrder;

class RefundController extends Controller
{
    use OtpGlobalTrait;
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification,UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }

    public function refundList()
    {
        $refundedOrders = RefundedOrder::latest()->paginate(10);
        $cancellationPolicy=OrderCancellationPolicy::first();
        foreach ($refundedOrders as $order)
        {
            $order->fine_type=$cancellationPolicy?->fine_type;
            $order->fine_amount=$cancellationPolicy?->amount;
        }
        return view('backend.pages.orders.refunded-list.all-list', compact('refundedOrders'));
    }

   
    // pagination
   public function paginate(Request $request)
    {

        if($request->ajax()){

            $query = RefundedOrder::with('user','provider','admin');

      

        $refundedOrders = $query->latest()->paginate(10);

        $cancellationPolicy=OrderCancellationPolicy::first();
        foreach ($refundedOrders as $order)
        {
            $order->fine_type=$cancellationPolicy?->fine_type;
            $order->fine_amount=$cancellationPolicy?->amount;
        }

            return view('backend.pages.orders.refunded-list.search-refunded-order', compact('refundedOrders'))->render();
        }
    }

    public function refundDetails(Request $request, $id)
    {
        $refundedOrder = RefundedOrder::with('user','provider','admin','order')
            ->where('id',$id)
            ->first();

        if (!$refundedOrder) {
            abort(404);
        }
        $gateway_details=RefundGateway::where('id',$refundedOrder->gateway_id)->first();
        $cancellationPolicy=OrderCancellationPolicy::first();
        $refundedOrder->fine_type=$cancellationPolicy?->fine_type;
        $refundedOrder->fine_amount=$cancellationPolicy?->amount;
        $refundedOrder->gateway_name=$gateway_details->name ?? null;
        $gateway_fields = json_decode($refundedOrder->gateway_fields,true);
       
        $refundedOrder->gateway_field=$gateway_fields;

        return view('backend.pages.orders.refunded-list.refund-details', compact('refundedOrder'));
    }

    public function changeRefundedStatus(Request $request){
        $id = $request->id;
        $status = $request->status_id;
        $refund_order = RefundedOrder::with('user','provider','admin')
            ->where('id',$id)
            ->first();

        if (! $refund_order) {
            abort(404);
        }

        $current_status = $refund_order->status;
        $old_status = '';
        $pending = __("Pending");
        $approved = __("Approved");
        $completed = __("Completed");
        $cancel = __("Canceled");

        // old order status
        if ($current_status == 0){
            $old_status = $pending;
        }elseif($current_status == 1){
            $old_status = $approved;
        }elseif($current_status == 2){
            $old_status = $completed;
        }elseif ($current_status == 3){
            $old_status = $cancel;
        }

        // new order status
        if($status==0){
            $new_status = 'Pending';
        }elseif($status==1){
            $new_status = 'Approved';
        }
        elseif($status==2){
            $new_status = 'Completed';
        }
        elseif($status==3){
            $new_status = 'Canceled';
        }

        $client_email = optional( $refund_order->user)->email;
        $client_firebase_token = optional( $refund_order->user)->firebase_token;
        // update
        $refund_order->update(['status' => $status]);

        //check whether status change from compelete to compelete or not
        if(  $current_status == 2 && $refund_order->status == 2 )
        {
            return redirect()->back()->with(FlashMsg::item_delete(__("Refunded Status Already Complete")));
        }
        else if($current_status == 3 && $refund_order->status == 3 )
        {
            return redirect()->back()->with(FlashMsg::item_delete(__("Refunded Status Already Cancel")));
        }
        //if($refund_order->status == 2)
        else if($refund_order->status == RefundStatusEnum::COMPLETE->value)
        // if(RefundStatusEnum::from($refund_order->status) == RefundStatusEnum::COMPLETE)
        {
            // RefundStatusEnum::from($refund_order->status)->label();
            //change provider earnings
            if($refund_order->provider_id != null)
            {
                $providerBalance = UserBalance::where('user_id',  $refund_order->provider_id)->first();
               
                if ($providerBalance &&  $refund_order->current_suborder_status == 2 &&  $refund_order->order?->payment_gateway != "cash_on_delivery") {
                    $providerBalance->update([
                        'available_balance' => $providerBalance->available_balance - $refund_order->amount,
                        'total_earnings' => $providerBalance->total_earnings - $refund_order->amount,
                        'total_refunds' => $providerBalance->total_refunds + $refund_order->amount,
                    ]);
                }
                else if($providerBalance && $refund_order->current_suborder_status == 2 &&  $refund_order->order?->payment_gateway == "cash_on_delivery")
                {
                    $providerBalance->update([
                        'total_earnings' => $providerBalance->total_earnings - $refund_order->amount,
                        'total_refunds' => $providerBalance->total_refunds + $refund_order->amount,
                    ]);
                }
            }

            if($refund_order->subOrder->is_refunded == 0)
            {
                $sub_order=SubOrder::where("id",$refund_order->sub_order_id)->first();
                $sub_order->is_refunded=1;
                $sub_order->save();
            }
        }

        else if($refund_order->status == RefundStatusEnum::CANCEL->value)
        {
            if($current_status == 2)
            {
                if($refund_order->provider_id != null)
                {
                    $providerBalance = UserBalance::where('user_id',  $refund_order->provider_id)->first();
                    $refunded_order= RefundedOrder::where('sub_order_id',  $refund_order->sub_order_id)->where("order_id", $refund_order->order_id)->first();
                    if ($providerBalance &&  $refund_order->current_suborder_status == 2 &&  $refund_order->order?->payment_gateway != "cash_on_delivery") {
                        $providerBalance->update([
                            'available_balance' => $providerBalance->available_balance + $refunded_order->amount,
                            'total_earnings' => $providerBalance->total_earnings + $refunded_order->amount,
                            'total_refunds' => $providerBalance->total_refunds - $refunded_order->amount,
                        ]);
                    }
                    else if($providerBalance && $refund_order->current_suborder_status == 2 &&  $refunded_order->order?->payment_gateway == "cash_on_delivery")
                    {
                        $providerBalance->update([
                            'total_earnings' => $providerBalance->total_earnings + $refunded_order->amount,
                            'total_refunds' => $providerBalance->total_refunds - $refunded_order->amount,
                        ]);
                    }
                }
            }
            $new_status=$request->new_status;
            $sub_order=SubOrder::where("id",$refund_order->sub_order_id)->first();
            $order = Order::find($sub_order->order_id);

            if(!$new_status || $new_status == 7)
            {
                $sub_order->status=$refund_order->current_suborder_status;
            }
            else
            {
                $sub_order->status=$new_status;
            }
           
            $sub_order->is_refunded=0;
            $sub_order->save();
             // Use the action to update the main order status
             $this->updateMainOrderStatus->execute($order, $sub_order);
        }
        else if( $current_status == 2 )
        {
            if($refund_order->provider_id != null)
            {
                $providerBalance = UserBalance::where('user_id',  $refund_order->provider_id)->first();
                $refunded_order= RefundedOrder::where('sub_order_id',  $refund_order->sub_order_id)->where("order_id", $refund_order->order_id)->first();
                if ($providerBalance &&  $refund_order->current_suborder_status == 2 &&  $refund_order->order?->payment_gateway != "cash_on_delivery") {
                    $providerBalance->update([
                        'available_balance' => $providerBalance->available_balance + $refunded_order->amount,
                        'total_earnings' => $providerBalance->total_earnings + $refunded_order->amount,
                        'total_refunds' => $providerBalance->total_refunds - $refunded_order->amount,
                    ]);
                }
                else if($providerBalance && $refund_order->current_suborder_status == 2 &&  $refunded_order->order?->payment_gateway == "cash_on_delivery")
                {
                    $providerBalance->update([
                        'total_earnings' => $providerBalance->total_earnings + $refunded_order->amount,
                        'total_refunds' => $providerBalance->total_refunds - $refunded_order->amount,
                    ]);
                }
            }

        }

         

            try {
                // Define the title and body based on the order status
                $statusMessages = [
                    0 => [
                        'title' => __("Refunded Order #:order_id Pending"),
                        'body' => __("Your refunded order #:order_id  and sub order #:sub_order_id has been created and is now pending.")
                    ],
                    1 => [
                       'title' => __("Refunded Order #:order_id Completed"),
                        'body' => __("Your refunded order #:order_id and sub order #:sub_order_id has been successfully completed.")
                    ],
                    2 => [
                        
                    ],
                    3 => [
                        'title' => __("Refunded Order #:order_id Cancelled"),
                        'body' => __("Your refunded order #:order_id  and sub order #:sub_order_id has been cancelled.")
                    ],
                 
                   
                ];


                if (!empty($client_firebase_token)) {
                    // Get the appropriate title and body based on the current status of the order
                    $title = $statusMessages[$order->status]['title'] ?? __("Refunded Order Status Changed");
                    $body = $statusMessages[$order->status]['body'] ?? __("The status of your refunded order has been updated.");

                    // Replace placeholders with actual order ID 
                    $title = str_replace([':order_id'], [$order->order_id], $title);
                    $title = str_replace([':sub_order_id'], [$order->sub_order_id], $title);
                    $body = str_replace([':order_id', ], [$order->order_id], $body);
                    $body = str_replace([':sub_order_id', ], [$order->sub_order_id], $body);

                    if (!empty($order->user)) {
                        user_notification($order->order_id, $order->client_id, 'order', $body, 'unread');
                    }

                    $client_notification_data = [
                        "title" => $title,
                        "detailed_title" => "-",
                        "identify" => $order->order_id, // identify
                        "user_id" => $order->client_id ?? 0, // user id
                        "body" => $body,
                        "description" => "-",
                        "type" => "order",
                        "sound" => "default",
                        "screen" => "-",
                    ];

                    // Pass the token as an array
                    $this->orderServiceNotification->sendFirebaseNotification([$client_firebase_token], $title, $body, $client_notification_data);

                }
            }catch (\Exception $e) { }


        // if order status change mail send to client and provider
        try {
            $order_status_change_title = __("Refunded Order Status Changed.") . $order->order_id;
            $message_status = __("Refunded Order Status Changed."). ' ' . __("Order ID:") .$order->order_id. ' ' . __("Sub Order ID:") .$order->sub_order_id;
            $message = str_replace(["@name","@old_status","@new_status","@order_id","@sub_order_id"],[$order->name,$old_status,$new_status,$order->order_id,$order->sub_order_id],$message_status);
            Mail::to($client_email)->send(new BasicMail([
                'subject' => $order_status_change_title,
                'message' => $message
            ]));

        } catch (\Exception $e) { }


        return redirect()->back()->with(FlashMsg::item_new(__("Refunded Status Change Success")));
    }

}
