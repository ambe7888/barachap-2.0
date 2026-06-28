<?php

use Illuminate\Support\Facades\Route;
use Modules\JobPost\app\Http\Controllers\Client\ClientFrontendJobController;
use Modules\JobPost\app\Http\Controllers\Client\ClientJobOrderPaymentController;
use Modules\JobPost\app\Http\Controllers\Client\ClientJobOrderPaymentIpnController;


Route::group(['prefix' => 'client/job'], function () {

    // Dashboard
    Route::controller(ClientFrontendJobController::class)->group(function(){
        Route::get('/all-jobs', 'job_list')->name('client.all.jobs');
        Route::match(['get','post'],'/create-job/{flag?}', 'create_job')->name('client.jobs.create');
        Route::match(['get','post'],'/edit-job/{id}', 'edit_client_job')->name('client.jobs.edit');
        Route::get('/job-details/{id}/{notificationId?}', 'job_details')->name('client.jobs.details');
        Route::get('/search','searchJob')->name('client.job.search');
        Route::get('/job-paginate','paginateJob')->name('client.job.paginate');
        Route::post('/published/{id}','job_published_status')->name('client.job.published.status.change');
        Route::post( '/delete/{id}','delete_job')->name('client.jobs.delete');


        //job offer
        Route::get( '/all-offers/{id}','offers_list')->name('client.offer-job.all');
        Route::get('/offer-paginate','paginateJobOffer')->name('client.offer-job.paginate');
        Route::get('/offer-details/{id}', 'offers_details')->name('client.offer-job.details');
        Route::post('/reject-job-offer', 'offers_reject')->name('client.offer-job.reject');
        Route::post('/delete-job-offer/{id}', 'offer_delete')->name('client.offer-job.delete');


    });

    //Job hire
    Route::controller(ClientJobOrderPaymentController::class)->group(function(){
        Route::post('payment-process','job_order_payment')->name('client.job.order.payment.process');

    });

    Route::controller(ClientJobOrderPaymentIpnController::class)->group(function(){

        Route::get('payment-process/stripe/ipn','payment_process_stripe_ipn')->name('client.job.order.payment.process.stripe.ipn');
        Route::post('payment-process/paytm-ipn','payment_process_paytm_ipn')->name('client.job.order.payment.process.paytm.ipn');
        Route::get('payment-process/paypal/ipn','payment_process_paypal_ipn')->name('client.job.order.payment.process.paypal.ipn');
        Route::get('payment-process/midtrans-ipn','payment_process_midtrans_ipn')->name('client.job.order.payment.process.midtrans.ipn');
        Route::post('payment-process/razorpay/ipn','payment_process_razorpay_ipn')->name('client.job.order.payment.process.razorpay.ipn');
        Route::get('payment-process/mollie/ipn','payment_process_mollie_ipn')->name('client.job.order.payment.process.mollie.ipn');
        Route::post('payment-process/payfast-ipn','payment_process_payfast_ipn')->name('client.job.order.payment.process.payfast.ipn');
        Route::get('payment-process/cashfree/ipn','payment_process_cashfree_ipn')->name('client.job.order.payment.process.cashfree.ipn');
        Route::get('payment-process/instamojo/ipn','payment_process_instamojo_ipn')->name('client.job.order.payment.process.instamojo.ipn');
        Route::get('payment-process/marcadopago/ipn','payment_process_marcadopago_ipn')->name('client.job.order.payment.process.marcadopago.ipn');
        Route::post('payment-process/zitopay-ipn','payment_process_zitopay_ipn')->name('client.job.order.payment.process.zitopay.ipn');
        Route::get('payment-process/squareup/ipn','payment_process_squareup_ipn')->name('client.job.order.payment.process.squareup.ipn');
        Route::post('payment-process/cinetpay-ipn','payment_process_cinetpay_ipn')->name('client.job.order.payment.process.cinetpay.ipn');
        Route::post('payment-process/paytabs-ipn','payment_process_paytabs_ipn')->name('client.job.order.payment.process.paytabs.ipn');
        Route::post('payment-process/billplz-ipn','payment_process_billplz_ipn')->name('client.job.order.payment.process.billplz.ipn');
        Route::post('payment-process/toyyibpay-ipn','payment_process_toyyibpay_ipn')->name('client.job.order.payment.process.toyyibpay.ipn');
        Route::get('payment-process/flutterwave/ipn','payment_process_flutterwave_ipn')->name('client.job.order.payment.process.flutterwave.ipn');

    });

});
