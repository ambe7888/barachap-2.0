<?php

use App\Http\Controllers\Api\AccountSettingController;
use App\Http\Controllers\Api\Provider\ProviderController;
use App\Http\Controllers\Api\Provider\ProviderDashboardController;
use App\Http\Controllers\Api\Provider\ProviderOrderController;
use App\Http\Controllers\Api\Provider\ProviderScheduleController;
use App\Http\Controllers\Api\Provider\ProviderServiceController;
use App\Http\Controllers\Api\Provider\ProviderStaffController;
use App\Http\Controllers\Api\Provider\ProviderWithdrawController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    Route::group(['prefix' => 'provider/','middleware' => 'auth:sanctum'],function (){
        // service create api
        Route::group(['prefix' => 'service/'],function (){
            Route::controller(ProviderServiceController::class)->group(function () {
                Route::get('/all-services','providerAllServices');
                Route::get('/category-wise-sub-category/{id}','subCategoryByCategory');
                Route::get('/subcategory-wise-child-category/{id}','childCategoryBySubcategory');
                Route::post('/add-service','addService');
                Route::post('/edit-service/{id}','editService');
                Route::post('/delete-service/{id}','deleteService');
                Route::get('/details/{id}','serviceDetails');
                // service status change like publish or unpublish
                Route::post('/published-status/{id}','servicePublishedStatus');
            });
            // for provider
            Route::post('location/update',[ProviderController::class,'serviceLocationUpdate']);

        });

        //Available Day list api
        Route::controller(ProviderScheduleController::class)->group(function () {
            Route::group(['prefix' => 'schedule/'],function (){
                Route::get('list', 'scheduleList');
                Route::post('create', 'scheduleCreate');
                Route::post('edit', 'scheduleUpdate');
                Route::post('delete', 'scheduleDelete');
             });
         });

        // Provider Staff Manage api
        Route::controller(ProviderStaffController::class)->group(function () {
            Route::group(['prefix' => 'staff/'],function (){
                Route::get('all-list', 'staffList');
                Route::post('create', 'staffCreate');
                Route::post('edit/{id}', 'staffEdit');
                Route::post('delete', 'staffDelete');
             });
         });

        // provider orders
        Route::group(['prefix' => 'orders'],function(){
            Route::controller(ProviderOrderController::class)->group(function (){
                Route::get('/all','myOrders');
                Route::get('/details/{id}','orderDetails');
                Route::post('/complete/request','orderStatus');
                Route::get('/complete-request/history','orderCompleteRequestHistory');
                // provider manage order status
                Route::post('/change-payment-status','codPaymentStatusChange');
                Route::post('/change-status-cancel','OrderStatusChangeCancel');
                Route::post('/order-accept-decline','orderAcceptDecline');
                // today order list
                Route::get('/today-lists','todayOrderList');
            });
        });
        // provider reviews
        Route::group(['prefix' => 'reviews'],function(){
            Route::controller(ProviderOrderController::class)->group(function (){
                Route::get('/all','allProviderReviews');
            });
        });
        // user account settings
        Route::group(['prefix' => 'settings/'],function(){
            Route::controller(AccountSettingController::class)->group(function (){
                Route::get('identity-verification/info','userProfileVerifyInfo');
                Route::post('identity-verification','userProfileVerify');
            });
        });
        // user account settings
        Route::group(['prefix' => 'dashboard/'],function(){
            Route::controller(ProviderDashboardController::class)->group(function (){
                Route::get('/info','dashboardInfo');
                Route::post('/get-total-income-data', 'getTotalIncomeData');
            });
        });
        //withdraw
        Route::controller(ProviderWithdrawController::class)->group(function(){
            Route::get('withdraw/settings','withdraw_settings');
            Route::post('withdraw/request','withdraw_request');
            Route::get('withdraw/history','withdraw_history');
            Route::get('current-balance/info','current_balance_info');
        });
         //refund request
         Route::group(['prefix' => 'refund'],function(){
            Route::controller(ProviderOrderController::class)->group(function (){
                Route::get('/refund-list','refundList');
                Route::get('/refund-details/{id}','refundDetails');
                Route::post('/refund-request-accept','acceptRefundRequest');
                Route::post('/refund-request-decline','declineRefundRequest');
            });
        });
   });
});


