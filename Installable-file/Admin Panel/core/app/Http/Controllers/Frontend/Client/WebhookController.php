<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Handlers\MessageHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        if (!empty($data['messages'])) {
            (new MessageHandler)->handleTextMessage($data['messages'][0]);
        } elseif (!empty($data['interactive'])) {
            (new MessageHandler)->handleInteractive($data['interactive']);
        }

        return response()->json(['status' => 'ok']);
    }
}
