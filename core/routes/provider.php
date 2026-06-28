<?php

use App\Http\Controllers\Frontend\ProviderStaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProviderServiceController;
use App\Http\Controllers\Frontend\MediaUploadController;
use App\Http\Controllers\Frontend\ProviderOrderController;
use App\Http\Controllers\Frontend\ScheduleManageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Frontend\ProviderRefundController;
use App\Http\Controllers\Frontend\ProviderReviewController;
use App\Http\Controllers\Frontend\ProviderNotificationController;
use App\Http\Controllers\Frontend\ProviderProfileController;
use App\Http\Controllers\Frontend\ProviderWithdrawController;
use App\Http\Controllers\Frontend\ProviderLanguageController;




Route::middleware(['provider','setlangforuser'])->group(function () {

     // Dashboard
     Route::prefix('provider')->group(function () {
        Route::controller(App\Http\Controllers\Frontend\ProviderDashboardController::class)->group(function(){
            Route::get('user-dashboard', 'userDashboard')->name('user.frontend.dashboard')->middleware('auth:web');
            Route::get('/get-total-income-data', 'getTotalIncomeData')->name('provider.get.total.income.graph.data');
         });
        Route::prefix('service')->group(function () {
            Route::controller(ProviderServiceController::class)->group(function () {
                Route::get("/all-services",[ProviderServiceController::class,'providerAllServices'])->name("provider.services.list");
                Route::match(['get','post'],'add','providerAddService')->name('provider.add.new.service');
                Route::match(['get','post'],'/provider-edit-service/{id?}','providerEditService')->name('provider.edit.service');
                Route::get('/details/{id}','serviceDetails')->name('provider.service.details');
                Route::get('/provider-search','providerSearchService')->name('provider.search.service');
                Route::get('/provider-paginate','providerPaginate')->name('provider.paginate.service');
                Route::post('/provider-delete/{id}','providerServiceDelete')->name('provider.delete.service');
                Route::post('/provider-bulk-action','bulkAction')->name('provider.bulk.action.service');
                Route::post('/provider-published/{id}','providerServicePublishedStatus')->name('provider.service.published.status.change.by');
                Route::post('/provider-status/{id}','providerChangeStatus')->name('provider.service.status.change.by');
                Route::post('/make-featured/{id}','makeFeatured')->name('provider.service.make.featured');
            });



        });

        Route::prefix('language')->group(function()
        {
            Route::controller(ProviderLanguageController::class)->group(function () {
                Route::match(['get','post'],'change-language','makeDefault')->name('provider.change.language');
            });
        });


        Route::prefix('profile')->group(function(){
            // admin profile settings
            Route::get('/user-logout', [ProviderProfileController::class, 'userLogout'])->name('user.logout');
            Route::get('/profile-update', [ProviderProfileController::class, 'userProfile'])->name('provider.profile.update');
            Route::post('/profile-update', [ProviderProfileController::class, 'userProfileUpdate']);
            Route::get('/password-change',[ProviderProfileController::class, 'userPassword'])->name('provider.profile.password.change');
            Route::post('/password-change',[ProviderProfileController::class, 'userPasswordChange']);
            Route::get('/user-account-delete-page',[ProviderProfileController::class, 'accountDeletePage'])->name('provider.account.delete.page');
            Route::post('/user-account-delete',[ProviderProfileController::class, 'accountDelete'])->name('provider.account.delete');
        });

         //schedule manage
         Route::group(['prefix' => 'schedule'],function(){
            Route::controller(ScheduleManageController::class)->group(function () {
                Route::match(['get','post'],'add_schedule','add_schedule')->name('provider.schedule.add');
                Route::get('/list','all_schedules')->name('provider.schedule.all');
                Route::post('/edit-schedule','edit_schedule')->name('provider.schedule.edit');
                Route::post('/delete/{id}','scheduleDelete')->name('provider.schedule.delete');
                Route::get('/search','search_schedule')->name('provider.schedule.search');
                Route::get('/paginate','schedule_paginate')->name('provider.schedule.paginate.data');
            });
        });

        //Staff Manage
        Route::group(['prefix'=>'staff'],function()
        {
            Route::controller(ProviderStaffController::class)->group(function()
            {
                Route::match(['get','post'],'add_staff','add_staff')->name('provider.staff.add');
                Route::get('/list','all_staffs')->name('provider.staff.all');
                Route::match(['get','post'],'/edit-staff/{id}','edit_staff')->name('provider.staff.edit');
                Route::post('/delete-staff/{id}','staffDelete')->name('provider.staff.delete');
                Route::get('/search','search-staff')->name('provider.staff.search');
                Route::get('/paginate','staff_paginate')->name('provider.staff.paginate');
                Route::post('/staff-bulk-action',"bulkAction")->name('provider.bulk.action.staff');
                Route::post('/staff-status/{id}','changeStatus')->name('provider.staff.status');

            });
        });

         //Staff Manage
         Route::group(['prefix'=>'review'],function()
         {
             Route::controller(ProviderReviewController::class)->group(function()
             {
                 Route::match(['get','post'],'all-reviews','allProviderReviews')->name('provider.reviews.all');
             });
         });

         //all notification
         Route::group(['prefix'=>'notificaton'],function(){

            Route::controller(ProviderNotificationController::class)->group(function()
            {
                Route::get('/all-notification','all_notification')->name('provider.notification.all');
                Route::get('/search','search_notification')->name('provider.notification.search');
                Route::get('/paginate','pagination')->name('provider.notification.paginate');
                Route::post('all/read','read_notification')->name('provider.notification.read');

            });
         });

          // manage withdraw
        Route::prefix('withdraw/')->group(function (){
            Route::controller(ProviderWithdrawController::class)->group(function () {
            Route::get('list','withdraw_history')->name('provider.withdraw.gateway');
            Route::match(['get','post'],'withdraw-request-send','withdraw_request')->name('provider.withdraw.request');
            Route::get('withdraw-request/details/{id?}/{notificationId?}','withdraw_request_details')->name('provider.withdraw.request.details');
            // Route::get('request/paginate/data', 'pagination')->name('admin.withdraw.paginate.data');
        });
    });

        Route::prefix('order')->group(function()
        {
            Route::controller(ProviderOrderController::class)->group(function()
            {

                Route::get('/all-orders','allOrders')->name('provider.all.orders');
                Route::get('/details/{id}/{notificationId?}','orderDetails')->name('provider.order.details');
                Route::get('/provider-sub-order-addon/details/{id}','subOrderAddonDetails')->name('provider.sub.order.addon.details');
                 Route::post('/status','changeStatus')->name('provider.order.status.change');
                 Route::get('/search','searchOrder')->name('provider.order.search');
                 Route::get('/paginate','paginate')->name('provider.order.paginate');
                // // payment status change
                // Route::post('/change-manual-payment-status/{id}','change_payment_status')->name('provider.order.change.status');
                // // order invoice generate
                 Route::get('invoice-details/{id?}', [InvoiceController::class, 'orderInvoiceGenerate'])->name('provider.order.invoice.generate');
                // // admin sub order manage
                Route::post('/sub-order-status','changeSubOrderStatus')->name('provider.sub.order.status.change');

                Route::post('/sub-order-status-accept-decline','orderAcceptDecline')->name('provider.sub.order.status.accept.decline');
                Route::post('/order-status-cancel','orderStatusChangeCancel')->name('provider.sub.order.status.cancel');
                Route::post('/order-complete-request/{sub_order_id}','orderCompleteRequest')->name('provider.sub.order.complete.request');

            });

            Route::get('refunded-order-list', [ProviderRefundController::class, 'refundList'])->name('provider.redunded-order.list');
            Route::get('/refund-details/{id}',[ProviderRefundController::class, 'refundDetails'])->name('provider.refunded-order.details');
            Route::post('refunded-order-accept-decline', [ProviderRefundController::class, 'acceptDeclineRefundRequest'])->name('provider.redunded-order.accept.decline');
           Route::get('/refunded-order-paginate',[ProviderRefundController::class, 'paginate'])->name('provider.refunded-order.paginate');

        });

    });
});

 // media upload routes end
 Route::post('/provider/media-upload/all', [MediaUploadController::class,'allUploadMediaFile'])->name('provider.upload.media.file.all');
 Route::post('/provider/media-upload', [MediaUploadController::class,'uploadMediaFile'])->name('provider.upload.media.file');
 Route::post('/provider/media-upload/alt', [MediaUploadController::class,'altChangeUploadMediaFile'])->name('provider.upload.media.file.alt.change');
 Route::post('/provider/media-upload/delete',[MediaUploadController::class, 'deleteUploadMediaFile'])->name('provider.upload.media.file.delete');
 // media upload routes for restrict user in demo mode
 Route::post('/provider/media-upload/loadmore',  [MediaUploadController::class,'getImageForLoadmore'])->name('provider.upload.media.file.loadmore');
