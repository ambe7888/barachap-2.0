<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email ' => $this->email,
            'phone' => $this->phone,
            'email_verified' => $this->email_verified,
            'verified_status' => $this->verified_status,
            'image' => ImageModifier::ImageUrl($this->image),
            'created_at' => $this->created_at,
        ];
    }
}
