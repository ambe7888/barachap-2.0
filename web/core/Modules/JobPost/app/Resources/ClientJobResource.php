<?php

namespace Modules\JobPost\app\Resources;

use App\Actions\Services\GalleryImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // Check if there is any job offer with is_hired == 1
        $hired_status = $this->job_offers->contains('is_hired', 1) ? 1 : 0;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'budget' => $this->budget,
            'view' => $this->view,
            'status' => $this->status,
            'hired_status' => $hired_status,
            'created_at' => $this->created_at,
            'job_offers_count' => $this->job_offers->count(),
        ];
    }
}
