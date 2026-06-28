<?php

namespace Modules\JobPost\app\Resources;

use App\Http\Resources\ProviderResource;
use App\Http\Resources\Users\ProviderPublicDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
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
            'provider' => $this->provider ? new ProviderPublicDetailsResource($this->provider) : null,
        ];
    }
}
