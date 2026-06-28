<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderAddonResource extends JsonResource
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
            'title' => $this->title,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'status' => $this->status,
        ];
    }
}
