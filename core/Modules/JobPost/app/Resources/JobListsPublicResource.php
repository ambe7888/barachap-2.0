<?php

namespace Modules\JobPost\app\Resources;

use App\Http\Resources\CategoryDetailsResource;
use App\Http\Resources\ChildCategoryDetailsResource;
use App\Http\Resources\SubCategoryDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListsPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category ? $this->category->name : null,
            'sub_category' => $this->sub_category ? $this->sub_category->name : null,
            'child_category' => $this->child_category ? $this->child_category->name : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'budget' => $this->budget,
            'view' => $this->view,
            'created_at' => $this->created_at,
            'image' => !empty(\App\Actions\Services\GalleryImageModifier::ImageUrl($this->gallery_images)) ? \App\Actions\Services\GalleryImageModifier::ImageUrl($this->gallery_images)[0] : null,
            'is_applied' => auth('sanctum')->check() ? \Modules\JobPost\app\Models\JobPostOffer::where('job_post_id', $this->id)->where('provider_id', auth('sanctum')->user()->id)->exists() : false,
            'job_location' => $this->job_location ? new JobLocationPublicResource($this->job_location) : null,
        ];
    }
}
