<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListForProviderResource extends JsonResource
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
            'payment_gateway' => $this->payment_gateway,
            'payment_status' => $this->payment_status,
            'transaction_id' => $this->transaction_id,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
            'subOrders' => SubOrderResource::collection($this->whenLoaded('subOrders')),
        ];
    }
}
