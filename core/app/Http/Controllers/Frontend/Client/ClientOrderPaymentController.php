<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Helpers\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\FlashMsg;
use Illuminate\Support\Facades\Auth;
use Modules\Coupon\app\Models\Coupon;
use App\Http\Services\OrderService;

class ClientOrderPaymentController extends Controller
{
    public function getTotalAfterDiscount(Request $request)
    {

        $order_id=$request->order_id;

        $order=Order::find($order_id);
        $total=$order->total;
        $coupon=Coupon::where(['code'=>$request->coupon_code, 'discount_type'=>$request->coupon_type])->first();
        $discount_amount=$coupon?->discount;

        if($total>$discount_amount)
        {
            if(!$coupon)
            {
                if($order?->coupon_code)
                {
                    if($order?->coupon_type == 'amount')
                    {
                        $total_after_discount=$total+$order->coupon_amount;

                    }
                    else if($order?->coupon_type == 'percentage')
                    {
                        $total_after_discount=$total+($total * ($order->coupon_amount / 100));

                    }

                    $total_after_discount_with_symbol=float_amount_with_currency_symbol($total_after_discount);

                }
            }

            if($order?->coupon_code)
            {
                if($order?->coupon_type == 'amount')
                {
                    $total=$total+$order->coupon_amount;
                    $total_after_discount=$total-$discount_amount;
                }
                else if($order?->coupon_type == 'percentage')
                {
                    $total=$total+($total * ($order->coupon_amount / 100));
                    $total_after_discount=$total-($total * ($discount_amount / 100));
                }

                $total_after_discount_with_symbol=float_amount_with_currency_symbol($total_after_discount);

            }
            else
            {
                if($coupon?->discount_type == 'amount')
                {
                    $total_after_discount=$total-$discount_amount;
                }
                else if($coupon?->discount_type == 'percentage')
                {
                    $total_after_discount=$total-($total * ($discount_amount / 100));
                }

                $total_after_discount_with_symbol=float_amount_with_currency_symbol($total_after_discount);
            }

            $order->total= $total_after_discount;
            $order->coupon_amount = $discount_amount;
            $order->coupon_code = $request->coupon_code;
            $order->coupon_type = $request->coupon_type;
            $order->save();


        }
        else{
            return response()->json([
                'status'=>'failed',
                'msg'=>'Please try another coupon code'
            ]);
        }


        return response()->json([
            'status'=>'success',
            'data' => $total_after_discount_with_symbol,
            'discount' =>float_amount_with_currency_symbol($discount_amount)
        ]);


    }

    public function payment_process($payment_gateway, $order_id){

        if($payment_gateway === 'stripe'){
            $stripe = PaymentGatewayCredential::get_stripe_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'stripe';
            $order->save();

            $response =  $stripe->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.stripe.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);


            return $response;
        }
        else if($payment_gateway === 'paytm')
        {
            $paytm = PaymentGatewayCredential::get_paytm_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'paytm';
            $order->save();
            $response =  $paytm->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.paytm.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'paypal')
        {
            $paypal = PaymentGatewayCredential::get_paypal_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'paypal';
            $order->save();
            $response =  $paypal->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.paypal.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'midtrans')
        {
            $midtrans = PaymentGatewayCredential::get_midtrans_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'midtrans';
            $order->save();
            $response =  $midtrans->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.midtrans.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);


            return $response;
        }
        else if($payment_gateway === 'razorpay')
        {
            $razorpay = PaymentGatewayCredential::get_razorpay_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'razorpay';
            $order->save();
            $response =  $razorpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.razorpay.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'mollie')
        {
            $mollie = PaymentGatewayCredential::get_mollie_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'mollie';
            $order->save();
            $response =  $mollie->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.mollie.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'payfast')
        {
            $payfast = PaymentGatewayCredential::get_payfast_credential();

            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'payfast';
            $order->save();
            $response =  $payfast->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.payfast.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
//                'success_url' =>route('client.order.payment.process.payfast.ipn'),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);
            return $response;
        }

        else if($payment_gateway === 'cashfree')
        {

            $cashfree = PaymentGatewayCredential::get_cashfree_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'cashfree';
            $order->save();
            $response =  $cashfree->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.cashfree.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'instamojo')
        {
            $instamojo = PaymentGatewayCredential::get_instamojo_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'instamojo';
            $order->save();
            $response =  $instamojo->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.instamojo.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'marcadopago')
        {
            $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'marcadopago';
            $order->save();
            $response =  $marcadopago->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.marcadopago.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'zitopay')
        {
            $zitopay = PaymentGatewayCredential::get_zitopay_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'zitopay';
            $order->save();
            $response =  $zitopay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.zitopay.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }

        else if ($payment_gateway === 'squareup')
        {
            $squareup = PaymentGatewayCredential::get_squareup_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'squareup';
            $order->save();

            $response =  $squareup->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.squareup.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'cinetpay')
        {
            $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'cinetpay';
            $order->save();
            $response =  $cinetpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.cinetpay.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'paytabs')
        {
            $paytabs = PaymentGatewayCredential::get_paytabs_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'paytabs';
            $order->save();
            $response =  $paytabs->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.paytabs.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'billplz')
        {
            $billplz = PaymentGatewayCredential::get_billplz_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'billplz';
            $order->save();
            $response =  $billplz->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.billplz.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'toyyibpay')
        {
            $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'toyyibpay';
            $order->save();
            $response =  $toyyibpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.toyyibpay.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'flutterwave')
        {
            $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'flutterwave';
            $order->save();
            $response =  $flutterwave->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.flutterwave.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }

        else if($payment_gateway === 'paystack')
        {
            $paystack = PaymentGatewayCredential::get_paystack_credential();
            $order=Order::where('id', $order_id)->first();
            $order->payment_gateway = 'paystack';
            $order->save();
            $response =  $paystack->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.order.payment.process.paystack.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'service-order',
            ]);

            return $response;
        }

        else if($payment_gateway === 'manual_payment')
        {
            $allowedSize = get_static_option('max_upload_size') ?? '5120';
            $allowedExtensions = json_decode(get_static_option('file_extensions'), true);
            $client_id = Auth::user()->id;

            if($allowedExtensions){
                $allowed_extensions = implode(',', $allowedExtensions);
                request()->validate([
                    'manual_payment_image' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                ]);
            }else{
                request()->validate(['manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf']);
            }
            return (new OrderService())->manual_order(request(), $client_id, $order_id);
        }
        else if($payment_gateway === 'cash_on_delivery') {
            $order = Order::where('id', $order_id)->first();
            $order->payment_gateway = 'cash_on_delivery';
            $order->save();

            return redirect()->route('client.order.payment.process.success', $order_id)->with(FlashMsg::item_update(__('Order placed successfully')));


        }else{
            return redirect()->route('client.order.payment.process.failed')->with(FlashMsg::item_update(__('Payment gateway not found')));
        }



    }

    public function payment_process_success($order_id)
    {
        $order_details=Order::find($order_id);
        return view('frontend.frontend.client.pages.order.payment_gateway.payment-success',compact('order_details'));
    }
    public function payment_process_failed()
    {
        return view('frontend.frontend.client.pages.order.payment_gateway.payment-failed');
    }

    public function order_payment_update(Request $request)
    {
        $request->validate([
            'order_id'=>'required|exists:orders,id',

        ],[
            'order_id.required'=>'Order id is required'
        ]);


        $order= Order::find($request->order_id);
        if($order->payment_status == 'complete'){
            return back()->with(FlashMsg::item_delete(__('Payment is already done.')));
        }

        $response=$this->payment_process($request->selected_payment_gateway, $order->id);
        return $response;



    }
}
