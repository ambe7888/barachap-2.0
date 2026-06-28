<?php

namespace App\Http\Resources\Orders;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCompleteRequestResource extends JsonResource
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
            'order_id' => $this->order_id,
            'sub_order_id' => $this->sub_order_id,
            'client_id' => $this->client_id,
            'provider_id' => $this->provider_id,
            'message' => $this->message,
            'status' => $this->status,
            'image' => ImageModifier::ImageUrl($this->image)
        ];
    }
}
