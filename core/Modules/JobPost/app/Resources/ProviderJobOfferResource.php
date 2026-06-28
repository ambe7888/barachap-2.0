<?php

namespace Modules\JobPost\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderJobOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'job_post_id' => $this->job_post_id,
            'client_id' => $this->client_id,
            'provider_id' => $this->provider_id,
            'budget' => $this->budget,
            'cover_letter' => $this->cover_letter,
            'is_hired' => $this->is_hired,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'job' => $this->job ? new JobSummaryResource($this->job) : null,
        ];
    }
}
