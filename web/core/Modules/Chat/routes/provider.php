<?php


use Modules\Chat\app\Http\Controllers\Frontend\ProviderChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['provider','setlangforuser'])->group(function () {

     Route::prefix('provider/live')->group(function () {
        Route::get('/chat', [ProviderChatController::class, 'live_chat'])->name('provider.live.chat');
        Route::post("/fetch-chat-provider-record", [ProviderChatController::class,'fetch_chat_record'])->name("provider.fetch.chat.client.record");
        Route::post('/message-send', [ProviderChatController::class,'message_send'])->name("provider.message.send");
     });
});        