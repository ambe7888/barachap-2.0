<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\Order\OrderReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderWithStaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $staffs_id;
    protected $disable_staff;

    // Accept additional parameters in the constructor
    public function __construct($resource, $staffsId, $disableStaff)
    {
        parent::__construct($resource);
        $this->staffs_id = $staffsId;
        $this->disable_staff = $disableStaff;
    }
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
        $return_arr = [
            'id' => $this->id,
            'full_name' => $this->fullname,
            'image' => ImageModifier::ImageUrl($this->image),
            'review_count' => $reviewCount,
            'average_rating' => number_format($averageRating, 1),
            'order_completion_rate' => round($order_completion_rate, 2),
            'customer_satisfaction_rate' => round($customer_satisfaction_rate, 2),
            'verified_status' => $this->verified_status,
            'last_seen' => $this->last_seen,
            'created_at' => $this->created_at,
            'service_categories' => CategoryListsResource::collection($provider_service_categories),
            'provider_service_area' => new UserServiceLocationResource($provider_service_locations),
        ];
        if($this->disable_staff=='1')
        {
            if($this->staffs_id)
            {
                $staffs_ids = array_map('intval', explode(',', $this->staffs_id));
                // Remove any 0 values
                $staffs_ids = array_filter($staffs_ids, fn($id) => $id > 0);

                // Re-index the array to ensure there are no gaps in the keys
                $staffs_ids = array_values($staffs_ids);
                $staffs = $this->providerStaff?->whereIn('id', $staffs_ids);
                
                $return_arr['staffs'] = StaffResource::collection($staffs);
                return  $return_arr;
            }
            else{
                if($this->providerStaff)
                {
                $return_arr['staffs'] = StaffResource::collection($this->providerStaff);
                return  $return_arr;
                }

            }

        }

        return  $return_arr;

    }
}
