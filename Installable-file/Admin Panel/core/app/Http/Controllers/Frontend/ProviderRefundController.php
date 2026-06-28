<?php

namespace App\Http\Controllers\Frontend;

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
use Illuminate\Support\Facades\Auth;
use App\Models\SubOrder;

class ProviderRefundController extends Controller
{
    
    use OtpGlobalTrait;
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification, UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }

    public function refundList()
    {
        $provider=Auth::user();
        $provider_id=$provider->id;
        $refundedOrders = RefundedOrder::where("provider_id",$provider_id)->latest()->paginate(10);
        $cancellationPolicy=OrderCancellationPolicy::first();
        foreach ($refundedOrders as $order)
        {
            $order->fine_type=$cancellationPolicy?->fine_type;
            $order->fine_amount=$cancellationPolicy?->amount;
        }
        return view('frontend.frontend.provider.pages.orders.refunded-list.all-list', compact('refundedOrders'));
    }

   
    // pagination
   public function paginate(Request $request)
    {

        if($request->ajax()){
            $provider=Auth::user();
            $provider_id=$provider->id;

            $query = RefundedOrder::with('user','provider','admin')->where("provider_id",$provider_id);

      

        $refundedOrders = $query->latest()->paginate(10);

        $cancellationPolicy=OrderCancellationPolicy::first();
        foreach ($refundedOrders as $order)
        {
            $order->fine_type=$cancellationPolicy?->fine_type;
            $order->fine_amount=$cancellationPolicy?->amount;
        }

            return view('frontend.frontend.provider.pages.orders.refunded-list.search-refunded-order', compact('refundedOrders'))->render();
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

        return view('frontend.frontend.provider.pages.orders.refunded-list.refund-details', compact('refundedOrder'));
    }

     //accept or decline refund request
     public function acceptDeclineRefundRequest(Request $request)
     {
         $request->validate([
             'refund_request_id' => 'required|integer|exists:refunded_orders,id',
             'status_id' => 'required|in:1,3', // 1=approve , 3=cancel
         ]);
 
         $provider_id = Auth::guard('sanctum')->user()->id;
 
         $refunded_order = RefundedOrder::where('id', $request->refund_request_id)
             ->where('provider_id', $provider_id)
             ->first();
 
         if (empty($refunded_order)) {

            return redirect()->back()->with(FlashMsg::item_delete(__('Refund request not found.')));
             
         }
 
         // Update the status of the refund request
         $refunded_order->status = (int) $request->status_id;
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
             }
 
         }  
         else if($refunded_order->status == 3){
            
            $sub_order = SubOrder::where('id', $refunded_order->sub_order_id)
                ->where('order_id', $refunded_order->order_id)
                ->first();
            $order=Order::where("id",$refunded_order->order_id)->first();       
            if (!empty($sub_order)) {
                $sub_order->status =  $refunded_order->current_suborder_status; // previous status
                $sub_order->is_refunded = 0;
                $sub_order->save();
                // update main order status
                $this->updateMainOrderStatus->execute($order, $sub_order);
                
            }

        }

         return redirect()->back()->with(FlashMsg::item_new(__("Refund request status updated successfully.")));
        
     }
 
}
