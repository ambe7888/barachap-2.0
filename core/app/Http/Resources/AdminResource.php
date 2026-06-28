<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\Services\ServiceLocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'image' => ImageModifier::ImageUrl($this->image),
            'service_location'=>new ServiceLocationResource($this->adminServiceLocation),
        ];
    }
}
