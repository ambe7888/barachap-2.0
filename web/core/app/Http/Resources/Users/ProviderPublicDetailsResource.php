<?php

namespace App\Http\Resources\Users;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\CategoryListsResource;
use App\Http\Resources\UserServiceLocationResource;
use App\Models\Backend\Category;
use App\Models\Review;
use App\Models\SubOrder;
use App\Models\UserServiceCategory;
use App\Models\UserServiceLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderPublicDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Fetch provider reviews and calculate average rating
        $reviewCount = $this->reviews->count();
        $averageRating = $reviewCount > 0 ? $this->reviews->avg('rating') : 0;

        // Use relationship to get suborder counts
        $suborderCounts = $this->subOrders()->selectRaw("
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_order,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_order,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete_order,
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as delivered_order,
            SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as cancelled_order
        ")->first();

        // Use relationship to get suborder counts
        $totalSuborderCounts = $this->subOrders()->selectRaw("
            SUM(CASE WHEN status = 2 AND service_id IS NOT NULL THEN 1 ELSE 0 END) as service_order_completed,
            SUM(CASE WHEN status = 2 AND job_post_id IS NOT NULL THEN 1 ELSE 0 END) as job_order_completed
        ")->first();

        // Get total completed service and job orders
        $total_service_order_completed = $totalSuborderCounts->service_order_completed ?? 0;
        $total_job_order_completed = $totalSuborderCounts->job_order_completed ?? 0;

        // Set order counts and calculate total orders and completion rate
        $pending_order = $suborderCounts->pending_order ?? 0;
        $active_order = $suborderCounts->active_order ?? 0;
        $complete_order = $suborderCounts->complete_order ?? 0;
        $delivered_order = $suborderCounts->delivered_order ?? 0;
        $cancelled_order = $suborderCounts->cancelled_order ?? 0;

        $total_orders = $pending_order + $active_order + $complete_order + $delivered_order + $cancelled_order;
        $order_completion_rate = $total_orders > 0 ? ($complete_order / $total_orders) * 100 : 0;

        // Calculate customer satisfaction rate from reviews
        $total_ratings = $this->reviews->count();
        $average_rating = $this->reviews->avg('rating');
        $customer_satisfaction_rate = $total_ratings > 0 ? ($average_rating / 5) * 100 : 0;

        // service categories using relationship
        $provider_service_categories = $this->serviceCategories;
        $provider_service_locations = $this->userServiceLocation;

        $storeImages=$this->store_images;
        $store_images=explode('|',$storeImages);
        $store_image=[];
        foreach($store_images as $image){
            $store_image[]=ImageModifier::ImageUrl($image);
        }

        return [
            'id' => $this->id,
            'full_name' => $this->fullname,
            'image' => ImageModifier::ImageUrl($this->image),
            'total_service_order_completed' => $total_service_order_completed,
            'total_job_order_completed' => $total_job_order_completed,
            'review_count' => $reviewCount,
            'average_rating' => number_format($averageRating, 1),
            'order_completion_rate' => round($order_completion_rate, 2),
            'customer_satisfaction_rate' => round($customer_satisfaction_rate, 2),
            'verified_status' => $this->verified_status,
            'last_seen' => $this->last_seen,
            'about'=> $this->about,
            'store_images'=> $store_image,
            'video_url'=> $this->video_url,
            'created_at' => $this->created_at,
            'service_categories' => CategoryListsResource::collection($provider_service_categories),
            'provider_service_area' => new UserServiceLocationResource($provider_service_locations),
        ];
    }
}
