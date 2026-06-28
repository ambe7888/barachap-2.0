<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefundedOrder;
use App\Models\OrderCancellationPolicy;
use Illuminate\Support\Facades\Auth;
use App\Models\RefundGateway;

class ClientRefundedOrderController extends Controller
{
   
    public function refundList()
    {
        $client_id=Auth::user()->id;
        $refundedOrders = RefundedOrder::where('client_id',$client_id)->latest()->paginate(10);
        $cancellationPolicy=OrderCancellationPolicy::first();
        foreach ($refundedOrders as $order)
        {
            $order->fine_type=$cancellationPolicy?->fine_type;
            $order->fine_amount=$cancellationPolicy?->amount;
        }
        return view('frontend.frontend.client.pages.order.refunded-list.all-list', compact('refundedOrders'));
    }

   
    // pagination
   public function paginate(Request $request)
    {

        if($request->ajax()){

            $client_id=Auth::user()->id;

            $query = RefundedOrder::where('client_id',$client_id)->with('user','provider','admin');

            $refundedOrders = $query->latest()->paginate(10);

            $cancellationPolicy=OrderCancellationPolicy::first();
            foreach ($refundedOrders as $order)
            {
                $order->fine_type=$cancellationPolicy?->fine_type;
                $order->fine_amount=$cancellationPolicy?->amount;
            }

            return view('frontend.frontend.client.pages.order.refunded-list.search-refunded-order', compact('refundedOrders'))->render();
        }
    }

    public function refundDetails(Request $request, $id)
    {
        $client_id=Auth::user()->id;
        $refundedOrder = RefundedOrder::with('user','provider','admin','order')
            ->where('client_id',$client_id)
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

        $gateway_details=unserialize($gateway_details->field);
        

        $gateway_methods=RefundGateway::all();
        


        return view('frontend.frontend.client.pages.order.refunded-list.refund-details', compact('refundedOrder','gateway_methods','gateway_details'));
    }


    public function editRefundGateway(Request $request)
    {
        $refund_id=$request->refund_id;
        $gateway_method=$request->gateway_method;
        $method_fields_for_change=$request->method_fields_for_change;
        $method_fields_for_change=json_encode($method_fields_for_change,true);

        $method_fields=$request->method_fields;
        $method_fields=json_encode($method_fields,true);

        if($method_fields_for_change)
        {
            RefundedOrder::where('id',$refund_id)->update([
                'gateway_id'=>$gateway_method,
                'gateway_fields' => $method_fields_for_change
            ]);
        }
        else
        {
            RefundedOrder::where('id',$refund_id)->update([
                'gateway_id'=>$gateway_method,
                'gateway_fields' => $method_fields
            ]);
        }

        return response()->json([
            'status' =>'success',
            'message' =>"Edit Successfully done"
        ]);


    }

    
}
