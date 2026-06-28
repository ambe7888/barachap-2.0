<?php

namespace Modules\Chat\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatActiveUserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'provider_id' => $this->provider_id,
            'admin_id' => $this->admin_id,
            'created_at' => $this->created_at,
            'client_unseen_msg_count' => $this->client_unseen_msg_count,
            'provider_unseen_msg_count' => $this->provider_unseen_msg_count,
        ];
    }
}
