<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\AdminResource;
use App\Http\Resources\ProviderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListForClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $this->subOrders->firstWhere('service_id') ? 'service' : ($this->subOrders->firstWhere('job_post_id') ? 'job' : 'unknown');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'sub_total' => $this->sub_total,
            'tax' => $this->tax,
            'total' => $this->total,
            'coupon_code' => $this->coupon_code,
            'coupon_type' => $this->coupon_type,
            'coupon_amount' => $this->coupon_amount,
            'payment_gateway' => $this->payment_gateway,
            'payment_status' => $this->payment_status,
            'transaction_id' => $this->transaction_id,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'type' => $type,
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
        ];
    }
}
