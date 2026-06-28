<?php

namespace Modules\JobPost\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobLocationPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
