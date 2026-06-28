<?php

namespace App\Http\Resources\Reviews;

use App\Http\Resources\Users\ClientPublicDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderPublicReviewCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviews = $this->reviews ?? collect(); // Default to empty collection if reviews is null
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $reviews->avg('rating') : 0;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'review_count' => $reviewCount,
            'average_rating' => $averageRating,
        ];
    }
}
