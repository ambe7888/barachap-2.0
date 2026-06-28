<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\Users\ClientPublicDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\JobPost\app\Resources\JobDetailsPublicResource;

class OrderDetailsResource extends JsonResource
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
            'client' => $this->client ? new ClientPublicDetailsResource($this->client) : null,
            'sub_total' => $this->sub_total,
            'coupon_code' => $this->coupon_code,
            'coupon_type' => $this->coupon_type,
            'coupon_amount' => $this->coupon_amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'payment_gateway' => $this->payment_gateway,
            'payment_status' => $this->payment_status,
            'transaction_id' => $this->transaction_id,
            'invoice_number' => $this->invoice_number,
            'commission_type' => $this->commission_type,
            'commission_charge' => $this->commission_charge,
            'commission_amount' => $this->commission_amount,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
            'subOrders' => SubOrderResource::collection($this->whenLoaded('subOrders')),
           
        ];
    }
}
