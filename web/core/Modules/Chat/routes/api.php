<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chat\app\Http\Controllers\Api\Client\ApiClientChatController;
use Modules\Chat\app\Http\Controllers\Api\Provider\ApiProviderChatController;


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    //provider route
    Route::group(['prefix'=>'provider', 'middleware' => 'auth:sanctum'],function(){
        Route::controller(ApiProviderChatController::class)->group(function(){
            Route::get('chat/client-list','client_list');
            Route::get('chat/fetch-record/{live_chat_id?}','fetch_record');
            Route::post('chat/message-send','message_send');
            Route::get('chat/unseen-message/count','unseen_message_count');
        });
    });

    //client route
    Route::group(['prefix'=>'client', 'middleware' => 'auth:sanctum'],function(){
        Route::controller(ApiClientChatController::class)->group(function(){
            Route::get('chat/provider-list','provider_list');
            Route::get('chat/fetch-record/{live_chat_id?}','fetch_record');
            Route::post('chat/message-send','message_send');
            Route::get('chat/unseen-message/count','unseen_message_count');
        });
    });

    // live chat credentials
    Route::get('live-chat/credentials',[ApiClientChatController::class, 'credentials']);

});
