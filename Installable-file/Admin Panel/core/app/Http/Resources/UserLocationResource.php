<?php

namespace App\Http\Resources;

use App\Http\Resources\Location\AreaResource;
use App\Http\Resources\Location\CityResource;
use App\Http\Resources\Location\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLocationResource extends JsonResource
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
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'state' => $this->state ? $this->state->state : null,
            'city' => $this->city ? $this->city->city : null,
            'area' => $this->area ? $this->area->area : null,
            'title' => $this->title,
            'phone' => $this->phone,
            'emergency_phone' => $this->emergency_phone,
            'address' => $this->address,
            'post_code' => $this->post_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'type' => $this->type,
        ];
    }
}
