<?php

namespace Modules\SupportTicket\app\Resources;

use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'message' => $this->message,
            'notify' => $this->notify,
            'attachment' => $this->attachment ? asset('assets/uploads/ticket/chat-messages/' . $this->attachment) : null,
            'type' => $this->type,
            'created_at' => $this->created_at ? $this->created_at->format('F j, Y') : '', // Human-readable format
        ];
    }
}
