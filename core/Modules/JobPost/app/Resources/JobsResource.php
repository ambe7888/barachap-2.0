<?php

namespace Modules\JobPost\app\Resources;

use App\Actions\Services\GalleryImageModifier;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\JobPost\app\Models\JobPostLocation;

class JobsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'child_category_id' => $this->child_category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'budget' => $this->budget,
            'view' => $this->view,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'is_published' => $this->is_published,
            'date' => $this->date,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'gallery_images' => GalleryImageModifier::ImageUrl($this->gallery_images),
            'job_location' => $this->job_location ? new JobLocationResource($this->job_location) : null,
            'job_offers' => JobOfferResource::collection($this->job_offers)->collection,
        ];
    }
}
