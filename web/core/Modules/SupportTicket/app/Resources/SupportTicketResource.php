<?php

namespace Modules\SupportTicket\app\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'department_id' => $this->department_id ? $this->department?->name: '',
            'admin_id' => $this->admin_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'subject' => $this->subject,
            'priority' => $this->priority,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at ? $this->created_at->format('F j, Y') : '', // Human-readable format
        ];
    }
}
