<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\app\Http\Controllers\Backend\PusherSettingsController;

require_once __DIR__ . '/client.php';
require_once __DIR__ . '/provider.php';
//admin routes
Route::group(['prefix'=>'admin','middleware' => ['auth:admin','setlang']],function(){
    Route::controller(PusherSettingsController::class)->group(function () {
        Route::match(['get','post'],'pusher/settings', 'pusher_settings')->name('admin.pusher.settings')->permission('live-chat-settings');
    });
});
