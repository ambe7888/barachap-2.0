<?php

namespace Modules\WhatsAppBookingSystem\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $receiverNumber;
    protected $message;
    public function __construct($receiverNumber,$message)
    {
        $this->receiverNumber = $receiverNumber;
        $this->message = $message;
    }

    public function handle()
    {

        $access_token = get_whatsapp_option('whatsapp_permanent_token') ?? '';
        $phone_number_id = get_whatsapp_option('whatsapp_phone_number_id') ?? '';

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $this->receiverNumber,
            'type' => 'text',
            'text' => [
                'body' => $this->message
            ]
        ];

        return Http::withToken($access_token)
            ->post("https://graph.facebook.com/v19.0/{$phone_number_id}/messages", $payload);
    }
}
