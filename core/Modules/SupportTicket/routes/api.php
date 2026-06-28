<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SupportTicket\app\Http\Controllers\Api\ClientSupportTicketController;


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    Route::group(['prefix' => 'user/','middleware' => 'auth:sanctum'],function (){

        // department get all
        Route::controller(ClientSupportTicketController::class)->group(function (){
            Route::get('all-departments','allDepartment');
        });

        // client support-tickets
        Route::group(['prefix' => 'support-ticket'],function(){
            Route::controller(ClientSupportTicketController::class)->group(function (){
                Route::get('all','allTickets');
                Route::get('/view-ticket/{id?}','viewTickets');
                Route::post('/message-send','sendMessage');
                // create ticket
                Route::post('/create','createTicket');
                Route::post('/send-otp-in-mail/success','sendOTPSuccess');
            });
        });
    });

});
