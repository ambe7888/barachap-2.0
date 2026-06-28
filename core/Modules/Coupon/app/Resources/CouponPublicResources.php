<?php

namespace Modules\Coupon\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponPublicResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'expire_date' => $this->expire_date,
            'status' => $this->status,
        ];
    }
}
