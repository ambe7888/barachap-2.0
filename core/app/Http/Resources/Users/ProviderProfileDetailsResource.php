<?php

namespace App\Http\Resources\Users;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\CategoryListsResource;
use App\Http\Resources\UserServiceLocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderProfileDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'image' => ImageModifier::ImageUrl($this->image),
            'verified_status' => $this->verified_status,
            'last_seen' => $this->last_seen,
            'terms_condition' => $this->terms_condition,
            'firebase_token' => $this->firebase_token,
            'check_online_status' => $this->check_online_status,
            'created_at' => $this->created_at,
            'about'=> $this->about,
            'store_images'=> $store_image,
            'video_url'=> $this->video_url,
            'service_categories' => CategoryListsResource::collection($provider_service_categories),
            'provider_service_area' => new UserServiceLocationResource($provider_service_locations),
        ];
    }
}
