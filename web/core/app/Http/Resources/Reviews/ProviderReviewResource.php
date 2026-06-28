<?php

namespace App\Http\Resources\Reviews;

use App\Http\Resources\Services\ServiceSummaryResource;
use App\Http\Resources\Users\ClientPublicDetailsResource;
use App\Http\Resources\Users\ProviderPublicDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderReviewResource extends JsonResource
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
            'user_type' => $this->user_type,
            'rating' => $this->rating,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'service_id' => $this->service_id,
            'job_id' => $this->job_id,
            'created_at' => $this->created_at,
            'service' => $this->service ? $this->service->title : null,
            'reviewer' => $this->reviewer ? new ClientPublicDetailsResource($this->reviewer) : null,
        ];
    }
}
