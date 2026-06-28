<?php

namespace Modules\Chat\app\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->id ?? null,
            'service_creator' => $this->service_creator ?? null,
            'username' => $this->username ?? null,
            'title' => $this->title ?? null,
            'slug' => $this->slug ?? null,
            'image' => ImageModifier::ImageUrl($this->image ?? null),
            'type' => $this->type ?? null,
            'interview_message' => $this->interview_message ?? null,
        ];
    }
}
