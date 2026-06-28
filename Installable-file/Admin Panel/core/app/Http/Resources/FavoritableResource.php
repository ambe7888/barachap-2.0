<?php

namespace App\Http\Resources;

use App\Http\Resources\Services\ServiceSummaryResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Resources\JobSummaryResource;

class FavoritableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Service) {
            return new ServiceSummaryResource($this->resource);
        } elseif ($this->resource instanceof JobPost) {
            return new JobSummaryResource($this->resource);
        }

        return [];
    }
}
