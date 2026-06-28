<?php

namespace App\Http\Resources\Services;

use App\Actions\Services\GalleryImageModifier;
use App\Actions\Services\ImageModifier;
use App\Http\Resources\AdminResource;
use App\Http\Resources\AdminWithStaffResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ChildCategoryResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\ProviderWithStaffResource;
use App\Http\Resources\Reviews\ServiceReviewResource;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\Users\ProviderPublicDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicsResource extends JsonResource
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
            'category' => $this->category ? new CategoryResource($this->category) : null,
            'sub_category' => $this->sub_category ? new SubCategoryResource($this->sub_category) : null,
            'child_category' => $this->child_category ? new ChildCategoryResource($this->child_category) : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'unit' => $this->unit,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'view' => $this->view,
            'sold_count' => $this->sold_count,
            'is_published' => $this->is_published,
            'status' => $this->status,
            'allocate_staff'=> $this->allocate_staff,
            'total_reviews' => $this->reviews->filter(function ($review) {
                return $review->reviewer && $review->reviewer->type == 1;
            })->count(),
            'average_rating' => number_format(
                $this->reviews
                    ->filter(function ($review) {
                        return $review->reviewer && $review->reviewer->type == 1;
                    })
                    ->avg('rating') ?? 0, 1
            ),
            'image' => ImageModifier::ImageUrl($this->image),
            'gallery_images' => GalleryImageModifier::ImageUrl($this->gallery_images),
            'video_url' =>$this->video_url?$this->video_url:null,
            'includes' => ServiceIncludesResource::collection($this->includes)->collection,
            'excludes' => ServiceExcludesResource::collection($this->excludes)->collection,
            'faqs' => ServicsFaqResource::collection($this->faqs)->collection,
            'addons' => ServicsAddonResource::collection($this->addons)->collection,
            'provider' => $this->provider ? new ProviderWithStaffResource($this->provider,$this->staffs_id,$this->disable_staff) : null,
            'admin' =>  $this->admin ? new AdminWithStaffResource($this->admin,$this->staffs_id,$this->disable_staff) : null,
            'reviews' => ServiceReviewResource::collection($this->reviews->filter(function ($review) {
                return $review->reviewer && $review->reviewer->type == 1;
            }))
        ];

    }
}
