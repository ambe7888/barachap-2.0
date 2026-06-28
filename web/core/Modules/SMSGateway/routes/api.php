<?php

use Illuminate\Support\Facades\Route;
use Modules\SMSGateway\app\Http\Controllers\Api\UserOtpManageController;



Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    Route::prefix('login-otp/')->group(function (){
        Route::controller(UserOtpManageController::class)->group(function () {
            Route::post('/send', 'sendOtp');
            Route::post('/verification', 'verifyOtp');
            Route::post('/resend', 'resendOtp');
            // user phone number check
            Route::post('login/user-phone-number-check','userPhoneNumberCheck')->name('phone.number.check');
        });
    });
});
