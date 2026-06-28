<?php

namespace App\Http\Resources;

use App\Http\Resources\Location\AreaResource;
use App\Http\Resources\Location\CityResource;
use App\Http\Resources\Location\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserServiceLocationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'state_name' => $this->state_id ? $this->state?->state : null,
            'city_name' => $this->city_id ? $this->city?->city : null,
            'area_name' => $this->area_id ? $this->area?->area : null,
            'post_code' => $this->post_code,
            'address' => $this->address,
             'longitude'=> $this->longitude,
            'latitude'=> $this->latitude,
        ];
    }
}
