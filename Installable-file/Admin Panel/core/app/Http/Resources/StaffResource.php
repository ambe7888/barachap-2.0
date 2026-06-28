<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'provider_id' => $this->provider_id,
            'admin_id' => $this->admin_id,
            'fullname' => $this->fullname,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'about' => $this->about,
            'status' => $this->status,
            'image' => ImageModifier::ImageUrl($this->image)
        ];
    }
}
