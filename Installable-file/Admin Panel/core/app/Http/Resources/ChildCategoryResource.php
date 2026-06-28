<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'image' => ImageModifier::ImageUrl($this->image)
        ];
    }
}
