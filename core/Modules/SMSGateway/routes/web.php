<?php

use Illuminate\Support\Facades\Route;
use Modules\SMSGateway\app\Http\Controllers\Backend\SmsSettingsController;

/*------------------ SMS SETTINGS MANAGE --------------*/
Route::group(['prefix' => 'sms-gateway-settings', 'middleware' => ['auth:admin', 'setlang']], function () {
    Route::get('/view',[SmsSettingsController::class, 'sms_settings'])->name('admin.sms.gateway.settings')->permission('sms-gateway-settings');
    Route::post('/update',[SmsSettingsController::class, 'update_sms_settings'])->name('admin.sms.gateway.update');
    Route::match(['get', 'post'], '/update-status', [SmsSettingsController::class, 'update_status'])->name('admin.sms.status')->permission('sms-gateway-status-change');
    Route::get('/login-otp-status', [SmsSettingsController::class, 'login_otp_status'])->name('admin.sms.login.otp.status');
    // sms settings controller
    Route::match(['get', 'post'], '/sms-options', [SmsSettingsController::class, 'update_sms_option_settings'])->name('admin.sms.options')->permission('sms-options-settings');
    // test sms
    Route::post('/test/otp', [SmsSettingsController::class, 'send_test_sms'])->name('admin.sms.test');
});
