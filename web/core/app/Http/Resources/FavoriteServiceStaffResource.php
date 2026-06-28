<?php

namespace App\Http\Resources;

use App\Http\Resources\Services\ServiceSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteServiceStaffResource extends JsonResource
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
            'service' =>  $this->service ? new ServiceSummaryResource($this->service) : null,
        ];
    }
}
