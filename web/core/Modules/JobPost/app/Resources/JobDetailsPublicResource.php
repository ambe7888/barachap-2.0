<?php

namespace Modules\JobPost\app\Resources;

use App\Actions\Services\GalleryImageModifier;
use App\Http\Resources\ClientResource;
use App\Http\Resources\Users\ClientPublicDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailsPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client' => $this->client ? new ClientPublicDetailsResource($this->client) : null,
            'category' => $this->category ? $this->category->name : null,
            'sub_category' => $this->sub_category ? $this->sub_category->name : null,
            'child_category' => $this->child_category ? $this->child_category->name : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'budget' => $this->budget,
            'view' => $this->view,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'date' => $this->date,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'gallery_images' => GalleryImageModifier::ImageUrl($this->gallery_images),
            'job_location' => $this->job_location ? new JobLocationResource($this->job_location) : null,
        ];
    }
}
