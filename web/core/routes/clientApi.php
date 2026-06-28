<?php

use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Client\ClientDashboardController;
use App\Http\Controllers\Api\Client\ClientOrderController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\UserLocationController;
use App\Http\Controllers\Api\Refund\RefundController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    // user Api Routes
    Route::group(['prefix' => 'client/','middleware' => 'auth:sanctum'],function (){
        // service order
        Route::group(['prefix' => 'service'],function (){
            Route::post('order-create',[OrderController::class,'serviceOrderCreate']);
            Route::post('order-payment-status-update',[OrderController::class,'paymentStatusUpdate']);
            Route::post('order-cancel',[OrderController::class,'cancelOrder']);
            Route::post('refund-info-update',[OrderController::class,'updatePaymentInfo']);
        });
        // client multiple address
        Route::group(['prefix' => 'location/'],function (){
            Route::post('create',[UserLocationController::class,'user_multiple_location_create']);
            Route::post('edit/{id}',[UserLocationController::class,'user_multiple_location_edit']);
            Route::post('delete/{id}',[UserLocationController::class,'user_multiple_location_delete']);
            Route::get('all',[UserLocationController::class,'user_all_multiple_location']);
       });
        // client all orders
        Route::group(['prefix' => 'orders'],function(){
            Route::controller(ClientOrderController::class)->group(function (){
                Route::get('all','clientAllOrders');
                Route::get('details/{id}','clientOrderDetails');
                Route::post('/complete-request/status-approve','orderCompleteRequestApprove');
                Route::post('/complete-request/status-decline','orderCompleteRequestDecline');
                Route::get('/complete-request/history','orderCompleteRequestHistory');
                Route::get('/all-refund-list','refundList');
                Route::post('/refund-details/{id}','refundedOrderDetails');
            });
        });

        Route::group(['prefix' => 'refund'],function(){
            Route::controller(RefundController::class)->group(function (){
                Route::get('/all','refund_gateway_list');
            });
        });
        // provider reviews
        Route::group(['prefix' => 'reviews'],function(){
            Route::controller(ClientOrderController::class)->group(function (){
                Route::get('/all','allClientReviews');
            });
        });

         // refund request
         Route::group(['prefix' => 'refund'],function(){
            Route::controller(ClientOrderController::class)->group(function (){
                Route::post('/request-send','refundRequestSend');
                Route::get('/request-history','refundRequestHistory');
            });
        });    
        // user account settings
        Route::group(['prefix' => 'dashboard/'],function(){
            Route::controller(ClientDashboardController::class)->group(function (){
                Route::get('/info','dashboardInfo');
            });
        });

        // tax info
        Route::get('/tax-info/{id}',[ClientController::class, 'taxInfo']);
        Route::get('/coupon-info/{coupon_code}',[ClientController::class, 'couponInfo']);

   });
});


