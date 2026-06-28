<?php


use Modules\Chat\app\Http\Controllers\Frontend\ClientChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['client','setlangforuser'])->group(function () {

     Route::prefix('client/live')->group(function () {
        Route::get('/chat', [ClientChatController::class, 'live_chat'])->name('client.live.chat');
         Route::post("/fetch-chat-provider-record", [ClientChatController::class,'fetch_chat_record'])->name("client.fetch.chat.provider.record");
          Route::post('/message-send', [ClientChatController::class,'message_send'])->name("client.message.send");
     });
});        