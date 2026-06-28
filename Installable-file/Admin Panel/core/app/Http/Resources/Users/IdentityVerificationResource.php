<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentityVerificationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'identification_type' => $this->identification_type,
            'identification_number' => $this->identification_number,
            'front_document' => $this->front_document ? asset('assets.front_document'.$this->attachment) : null,
            'back_document' => $this->back_document ? asset('assets.back_document'.$this->attachment) : null,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
