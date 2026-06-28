<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderReviewResource extends JsonResource
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
            'reviewer_id' => $this->reviewer_id,
            'rating' => $this->rating,
            'order_id' => $this->order_id,
            'sub_order_id' => $this->sub_order_id,
            'service_id' => $this->service_id,
            'job_id' => $this->job_id,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->user),
        ];
    }
}
