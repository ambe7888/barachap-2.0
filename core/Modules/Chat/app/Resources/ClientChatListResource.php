<?php

namespace Modules\Chat\app\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\SupportTicket\app\Models\ChatMessage;

class ClientChatListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client_name' => $this->client_id ? $this->client?->fullname : null,
            'client_image' => $this->client_id ? ImageModifier::ImageUrl($this->client?->image) : null,
            'provider_id' => $this->provider_id,
            'provider_name' => $this->provider_id ? $this->provider?->fullname : null,
            'provider_image' => $this->provider_id ? ImageModifier::ImageUrl($this->provider?->image) : null,
            'admin_id' => $this->admin_id,
            'created_at' => $this->created_at,
            'client_unseen_msg_count' => $this->client_unseen_msg_count,
            'provider_unseen_msg_count' => $this->provider_unseen_msg_count,
            // Check if last_message exists and get the first one
            'last_message' => $this->livechatMessage ? new LiveChatSingleMessageResource($this->livechatMessage->first()) : null,
        ];
    }
}
