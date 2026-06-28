<?php

namespace Modules\JobPost\app\Resources;

use App\Actions\Services\GalleryImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'budget' => $this->budget,
            'created_at' => $this->created_at,
        ];
    }
}
