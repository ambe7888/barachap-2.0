<?php

namespace App\Http\Resources\Services;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicsAddonResource extends JsonResource
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
            'service_id' => $this->service_id,
            'title' => $this->title,
            'price' => $this->price,
            'image' => ImageModifier::ImageUrl($this->image),
            'description' => $this->description,
        ];
    }
}
