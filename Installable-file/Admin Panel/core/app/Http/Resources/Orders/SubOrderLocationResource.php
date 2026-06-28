<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderLocationResource extends JsonResource
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
            'sub_order_id' => $this->sub_order_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'title' => $this->title,
            'post_code' => $this->post_code,
            'address' => $this->address,
            'phone' => $this->phone,
            'emergency_phone' => $this->emergency_phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'type' => $this->type,
        ];
    }
}
