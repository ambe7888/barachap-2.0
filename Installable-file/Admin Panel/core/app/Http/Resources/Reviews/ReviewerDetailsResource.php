<?php

namespace App\Http\Resources\Reviews;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewerDetailsResource extends JsonResource
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
            'fullname' => $this->first_name . ' ' . $this->last_name,
            'type' => $this->type,
            'image' => $this->image ? ImageModifier::ImageUrl($this->image) : null,
        ];
    }
}
