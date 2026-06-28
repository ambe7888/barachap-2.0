<?php

namespace Modules\Chat\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LiveChatMessageListsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'live_chat_id' => $this->live_chat_id,
            'from_user' => $this->from_user,
            'message_title' => $this->message['message'],
            'message' => isset($this->message['service']) && is_array($this->message['service'])
                ? new LiveChatMessageResource((object)$this->message['service']) // Cast to object if necessary
                : null,
            'file' => $this->file,
            'is_seen' => $this->is_seen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
