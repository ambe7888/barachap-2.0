<?php

use App\Http\Controllers\Frontend\Client\ClientOrderIpnController;
use App\Http\Controllers\Frontend\Client\ClientProfileController;
use App\Http\Controllers\Frontend\ProviderStaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProviderServiceController;
use App\Http\Controllers\Frontend\Client\MediaUploadController;
use App\Http\Controllers\Frontend\Client\ClientOrderController;
use App\Http\Controllers\Frontend\ScheduleManageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Frontend\ProviderRefundController;
use App\Http\Controllers\Frontend\Client\ClientReviewController;
use App\Http\Controllers\Frontend\Client\ClientNotificationController;
use App\Http\Controllers\Frontend\ProviderProfileController;
use App\Http\Controllers\Frontend\ProviderWithdrawController;
use App\Http\Controllers\Frontend\Client\ClientLanguageController;
use App\Http\Controllers\Frontend\Client\ClientFavouriteItemController;
use App\Http\Controllers\Frontend\Client\ClientLocationController;
use App\Http\Controllers\Frontend\Client\ClientRefundedOrderController;
use App\Http\Controllers\Frontend\Client\ClientOrderPaymentController;

Route::middleware(['client','setlangforuser'])->group(function () {



     // Dashboard
     Route::prefix('client')->group(function () {
        Route::controller(App\Http\Controllers\Frontend\Client\ClientDashboardController::class)->group(function(){
            Route::get('dashboard', 'userDashboard')->name('client.frontend.dashboard');

         });

        Route::prefix('language')->group(function()
        {
            Route::controller(ClientLanguageController::class)->group(function () {
                Route::match(['get','post'],'change-language','makeDefault')->name('client.change.language');
            });
        });


        Route::prefix('profile')->group(function(){
            // admin profile settings
            Route::get('/user-logout', [ClientProfileController::class, 'userLogout'])->name('client.logout');
            Route::get('/profile-update', [ClientProfileController::class, 'userProfile'])->name('client.profile.update');
            Route::post('/profile-update', [ClientProfileController::class, 'userProfileUpdate']);
            Route::get('/password-change',[ClientProfileController::class, 'userPassword'])->name('client.profile.password.change');
            Route::post('/password-change',[ClientProfileController::class, 'userPasswordChange']);
            Route::get('/user-account-delete-page',[ClientProfileController::class, 'accountDeletePage'])->name('client.account.delete.page');
            Route::post('/user-account-delete',[ClientProfileController::class, 'accountDelete'])->name('client.account.delete');
        });





         //Staff Manage
         Route::group(['prefix'=>'review'],function()
         {
             Route::controller(ClientReviewController::class)->group(function()
             {
                 Route::match(['get','post'],'all-reviews','allClientReviews')->name('client.reviews.all');
                 //pagination
                Route::get('/pagination','pagination')->name('client.reviews.pagination');
             });
         });

         //all notification
         Route::group(['prefix'=>'notificaton'],function(){

            Route::controller(ClientNotificationController::class)->group(function()
            {
                Route::get('/all-notification','all_notification')->name('client.notification.all');
                Route::get('/search','search_notification')->name('client.notification.search');
                Route::get('/paginate','pagination')->name('client.notification.paginate');
                Route::post('all/read','read_notification')->name('client.notification.read');

            });
         });

         //all favourite Service
         Route::group(['prefix'=>'favourite-service'],function(){

            Route::controller(ClientFavouriteItemController::class)->group(function()
            {
                Route::get('/all-favourite-service','favoriteLists')->name('client.favourite.services.all');
                Route::get('/paginate','pagination')->name('client.favourite.services.paginate');
                Route::post('remove-favourite-services/{id}','favoriteRemove')->name('client.favourite.services.remove');
                Route::get('/details/{id}','favoriteDetails')->name('client.favourite.services.details');

            });
         });

          //client location
          Route::group(['prefix'=>'location'],function(){

            Route::controller(ClientLocationController::class)->group(function()
            {
                Route::get('/all-client-location','user_all_multiple_location')->name('client.location.all');
                Route::match(['get','post'],'add-location/{flag?}','user_multiple_location_create')->name('client.location.add');
                Route::get('/location-map/{flag?}/{job_id?}','user_multiple_location_map_page')->name('client.location.map');
                Route::get('/edit-map-location/{id}','user_multiple_location_map_edit_page')->name('client.location.map.edit');
                Route::match(['get','post'],'/edit-location/{id}','user_multiple_location_edit')->name('client.location.edit');
                Route::get('/paginate','pagination')->name('client.favourite.services.paginate');
                Route::post('delete-location/{id}','user_multiple_location_delete')->name('client.location.delete');
                Route::get('/details/{id}','favoriteDetails')->name('client.favourite.services.details');
                Route::get('/get-cities/{stateId}','getCity')->name('client.getCity');
                Route::get('/get-areas/{cityId}/{stateId}','getArea')->name('client.getArea');

            });
         });



        Route::prefix('order')->group(function()
        {
            Route::controller(ClientOrderController::class)->group(function()
            {

                Route::get('/all-orders','allOrders')->name('client.all.orders');
                Route::get('/details/{id}/{notificationId?}','orderDetails')->name('client.order.details');
                Route::get('/client-sub-order/details/{id}','subOrderDetails')->name('client.sub.order.details');
                //  Route::post('/status','changeStatus')->name('provider.order.status.change');
                 Route::get('/search','searchOrder')->name('client.order.search');
                 Route::get('/paginate','paginate')->name('client.order.paginate');
                // // payment status change
                // Route::post('/change-manual-payment-status/{id}','change_payment_status')->name('provider.order.change.status');
                // // order invoice generate
                 Route::get('invoice-details/{id?}', [InvoiceController::class, 'orderInvoiceGenerate'])->name('client.order.invoice.generate');

                 //complete request approve
                Route::post('/order-complete-request-approve','orderCompleteRequestApprove')->name('client.order.complete.request.approve');

                //decline complete request
                Route::post('/order-complete-request-decline','orderCompleteRequestDecline')->name('client.order.complete.request.decline');

                //cancel order
                Route::post('/order-cancel-request','cancelOrder')->name('client.order.cancel.request');

                //refund request
                Route::post('/refund-request-send','refundRequestSend')->name('client.refund.request.send');
                 Route::get('/order-service-details/{id}','serviceDetails')->name('client.order.service.details');

                // Route::post('/sub-order-status-accept-decline','orderAcceptDecline')->name('provider.sub.order.status.accept.decline');
                // Route::post('/order-status-cancel','orderStatusChangeCancel')->name('provider.sub.order.status.cancel');
                // Route::post('/order-complete-request/{sub_order_id}','orderCompleteRequest')->name('provider.sub.order.complete.request');

            });

            Route::controller(ClientOrderPaymentController::class)->group(function(){
                Route::post('/get-amount-after-discount','getTotalAfterDiscount')->name('client.order.total.after-discount');
                Route::post('payment-process','order_payment_update')->name('client.order.payment.process');

                Route::get('payment-process/success/{order_id}','payment_process_success')->name('client.order.payment.process.success');
                Route::get('payment-process/failed','payment_process_failed')->name('client.order.payment.process.failed');
            });

            Route::controller(ClientOrderIpnController::class)->group(function(){

                Route::get('payment-process/stripe/ipn','payment_process_stripe_ipn')->name('client.order.payment.process.stripe.ipn');
                Route::post('payment-process/paytm-ipn','payment_process_paytm_ipn')->name('client.order.payment.process.paytm.ipn');
                Route::get('payment-process/paypal/ipn','payment_process_paypal_ipn')->name('client.order.payment.process.paypal.ipn');
                Route::get('payment-process/midtrans-ipn','payment_process_midtrans_ipn')->name('client.order.payment.process.midtrans.ipn');
                Route::post('payment-process/razorpay/ipn','payment_process_razorpay_ipn')->name('client.order.payment.process.razorpay.ipn');
                Route::get('payment-process/mollie/ipn','payment_process_mollie_ipn')->name('client.order.payment.process.mollie.ipn');
                Route::post('payment-process/payfast-ipn','payment_process_payfast_ipn')->name('client.order.payment.process.payfast.ipn');
                Route::get('payment-process/cashfree/ipn','payment_process_cashfree_ipn')->name('client.order.payment.process.cashfree.ipn');
                Route::get('payment-process/instamojo/ipn','payment_process_instamojo_ipn')->name('client.order.payment.process.instamojo.ipn');
                Route::get('payment-process/marcadopago/ipn','payment_process_marcadopago_ipn')->name('client.order.payment.process.marcadopago.ipn');
                Route::post('payment-process/zitopay-ipn','payment_process_zitopay_ipn')->name('client.order.payment.process.zitopay.ipn');
                Route::get('payment-process/squareup/ipn','payment_process_squareup_ipn')->name('client.order.payment.process.squareup.ipn');
                Route::post('payment-process/cinetpay-ipn','payment_process_cinetpay_ipn')->name('client.order.payment.process.cinetpay.ipn');
                Route::post('payment-process/paytabs-ipn','payment_process_paytabs_ipn')->name('client.order.payment.process.paytabs.ipn');
                Route::post('payment-process/billplz-ipn','payment_process_billplz_ipn')->name('client.order.payment.process.billplz.ipn');
                Route::post('payment-process/toyyibpay-ipn','payment_process_toyyibpay_ipn')->name('client.order.payment.process.toyyibpay.ipn');
                Route::get('payment-process/flutterwave/ipn','payment_process_flutterwave_ipn')->name('client.order.payment.process.flutterwave.ipn');
                Route::get('payment-process/paystack-ipn','payment_process_paystack_ipn')->name('client.order.payment.process.paystack.ipn');

            });




            Route::prefix('review')->group(function(){
                Route::controller(ClientReviewController::class)->group(function(){
                    Route::post('/send-review','orderReviewAdd')->name('client.review.send');

                });

            });

            Route::prefix('refunded-order')->group(function(){
                Route::controller(ClientRefundedOrderController::class)->group(function(){
                    Route::get('refunded-order-list', 'refundList')->name('client.redunded-order.list');
                    Route::get('/refund-details/{id}','refundDetails')->name('client.refunded-order.details');
                    Route::get('/refunded-order-paginate','paginate')->name('client.refunded-order.paginate');
                    Route::post('/edit-refund-gateway','editRefundGateway')->name('client.refund.gateway.edit');
                });
            });


        });

    });
});

 // media upload routes end
 Route::post('/client/media-upload/all', [MediaUploadController::class,'allUploadMediaFile'])->name('client.upload.media.file.all');
 Route::post('/client/media-upload', [MediaUploadController::class,'uploadMediaFile'])->name('client.upload.media.file');
 Route::post('/client/media-upload/alt', [MediaUploadController::class,'altChangeUploadMediaFile'])->name('client.upload.media.file.alt.change');
Route::post('/client/media-upload/delete',[MediaUploadController::class, 'deleteUploadMediaFile'])->name('client.upload.media.file.delete');
 // media upload routes for restrict user in demo mode
 Route::post('/client/media-upload/loadmore',  [MediaUploadController::class,'getImageForLoadmore'])->name('client.upload.media.file.loadmore');
