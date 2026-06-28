<?php

namespace App\Http\Resources\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admin_id' => $this->admin_id,
            'state_id'=> $this->state_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'address' => $this->address,
            'post_code' => $this->post_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
            
        ];
    }
}
