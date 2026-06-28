<?php

namespace Modules\JobPost\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'job_post_id' => $this->job_post_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'phone' => $this->phone,
            'post_code' => $this->post_code,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
