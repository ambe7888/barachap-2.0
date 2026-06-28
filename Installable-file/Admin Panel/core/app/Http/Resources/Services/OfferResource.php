<?php

namespace App\Http\Resources\Services;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'image' => $this->image ? ImageModifier::ImageUrl($this->image) : '',
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'is_primary' => $this->is_primary,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expaires_at'=>$this->expires_at
            
            
        ];
    }
}
