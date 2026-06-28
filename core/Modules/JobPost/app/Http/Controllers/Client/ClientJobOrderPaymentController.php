<?php

namespace Modules\JobPost\app\Http\Controllers\Client;

use App\Helpers\FlashMsg;
use App\Helpers\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Coupon\app\Models\Coupon;
use Modules\JobPost\app\Models\JobPostOffer;

class ClientJobOrderPaymentController extends Controller
{
    public function payment_process($payment_gateway, $order_id,$job_post_id,$job_post_offer_id){

        session()->put('job_post_id', $job_post_id);
        session()->put('job_post_offer_id', $job_post_offer_id);

        if($payment_gateway === 'stripe'){
            $stripe = PaymentGatewayCredential::get_stripe_credential();
            $order=Order::where('id', $order_id)->first();

            $response =  $stripe->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.stripe.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'paytm')
        {
            $paytm = PaymentGatewayCredential::get_paytm_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $paytm->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.paytm.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'paypal')
        {
            $paypal = PaymentGatewayCredential::get_paypal_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $paypal->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.paypal.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'midtrans')
        {
            $midtrans = PaymentGatewayCredential::get_midtrans_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $midtrans->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.midtrans.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);


            return $response;
        }
        else if($payment_gateway === 'razorpay')
        {
            $razorpay = PaymentGatewayCredential::get_razorpay_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $razorpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.razorpay.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'mollie')
        {
            $mollie = PaymentGatewayCredential::get_mollie_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $mollie->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.mollie.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'payfast')
        {
            $payfast = PaymentGatewayCredential::get_payfast_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $payfast->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.payfast.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }

        else if($payment_gateway === 'cashfree')
        {

            $cashfree = PaymentGatewayCredential::get_cashfree_credential();
            $order=Order::where('id', $order_id)->first();
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
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'instamojo')
        {
            $instamojo = PaymentGatewayCredential::get_instamojo_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $instamojo->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.instamojo.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'marcadopago')
        {
            $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $marcadopago->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.marcadopago.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'zitopay')
        {
            $zitopay = PaymentGatewayCredential::get_zitopay_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $zitopay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.zitopay.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }

        else if ($payment_gateway === 'squareup')
        {
            $squareup = PaymentGatewayCredential::get_squareup_credential();
            $order=Order::where('id', $order_id)->first();

            $response =  $squareup->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.squareup.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'cinetpay')
        {
            $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $cinetpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.cinetpay.ipn'), //get route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'paytabs')
        {
            $paytabs = PaymentGatewayCredential::get_paytabs_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $paytabs->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.paytabs.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'billplz')
        {
            $billplz = PaymentGatewayCredential::get_billplz_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $billplz->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.billplz.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'toyyibpay')
        {
            $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $toyyibpay->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.toyyibpay.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }
        else if($payment_gateway === 'flutterwave')
        {
            $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
            $order=Order::where('id', $order_id)->first();
            $response =  $flutterwave->charge_customer([
                'amount' => $order->total,
                'title' => 'Order',
                'description' => 'Order #'. $order_id.' Email: '.$order->client?->email.' Name: '.$order->client?->fullName,
                'ipn_url' => route('client.job.order.payment.process.flutterwave.ipn'), //post route
                'order_id' => $order_id,
                'track' => \Str::random(36),
                'cancel_url' => route('client.order.payment.process.failed'),
                'success_url' => route('client.order.payment.process.success',$order_id),
                'email' => Auth::user()->email,
                'name' => Auth::user()->fullName,
                'payment_type' => 'job-order',
            ]);

            return $response;
        }

        else if($payment_gateway === 'paystack')
        {
            $paystack = PaymentGatewayCredential::get_paystack_credential();
            $order=Order::where('id', $order_id)->first();
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
                'payment_type' => 'job-order',
            ]);

            return $response;
        }

        else if($payment_gateway == 'manual_payment')
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
            return (new OrderService())->manual_job_order(request(), $client_id, $order_id, $job_post_id, $job_post_offer_id);
        }
        else if($payment_gateway === 'cash_on_delivery') {
            $order = Order::where('id', $order_id)->first();
            $order->payment_gateway = 'cash_on_delivery';
            $order->save();
            $job_post_offer=JobPostOffer::where('id',$job_post_offer_id)->where('job_post_id',$job_post_id)->first();
            if ($job_post_offer) {
                $job_post_offer->status = 1;
                $job_post_offer->save();
            }

            return redirect()->route('client.order.payment.process.success', $order_id)->with(FlashMsg::item_update(__('Order placed successfully')));


        }

        else{
            return redirect()->route('client.order.payment.process.failed')->with(FlashMsg::item_update(__('Payment gateway not found')));
        }



    }



    public function job_order_payment(ClientJobHireController $job_hire,Request $request)
    {

        $request->validate([
            'job_offer_id' => 'required|exists:job_post_offers,id',
            'job_post_id'=>'required|exists:job_posts,id',
            'selected_payment_gateway' => 'required',

        ],[
            'job_offer_id.required'=>'Job Offer id is required',
            'job_post_id.required'=>'Job Post id is required',
            'selected_payment_gateway.required'=>'Payment gateway is required',
        ]);


        $job_post_id = $request->job_post_id;
        $job_offer_id = $request->job_offer_id;

        $order=$job_hire->job_hire($job_offer_id,$job_post_id, $request->selected_payment_gateway);

        if ($order instanceof \Illuminate\Http\RedirectResponse) {
            return $order;
        }

        $response=$this->payment_process($request->selected_payment_gateway, $order->id, $job_post_id, $job_offer_id);
        return $response;



    }
}
