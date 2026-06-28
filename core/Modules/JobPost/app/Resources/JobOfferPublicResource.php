<?php

namespace Modules\JobPost\app\Resources;

use App\Http\Resources\ProviderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider ? new ProviderResource($this->provider) : null,
            'budget' => $this->budget,
            'cover_letter' => $this->cover_letter,
            'is_hired' => $this->is_hired,
            'status' => $this->status,
        ];
    }
}
