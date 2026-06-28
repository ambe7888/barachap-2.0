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
            'created_at' => $this->created_at,
            'job_location' => $this->job_location ? new JobLocationPublicResource($this->job_location) : null,
        ];
    }
}
