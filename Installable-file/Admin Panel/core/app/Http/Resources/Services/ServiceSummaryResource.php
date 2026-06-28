<?php

namespace App\Http\Resources\Services;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\AdminResource;
use App\Http\Resources\CategoryDetailsResource;
use App\Http\Resources\ChildCategoryDetailsResource;
use App\Http\Resources\Reviews\ServiceReviewResource;
use App\Http\Resources\StaffResource;
use App\Http\Resources\SubCategoryDetailsResource;
use App\Http\Resources\Users\ProviderPublicDetailsResource;
use App\Http\Resources\Users\ProviderShortInfoResource;
use App\Models\Offer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSummaryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
      
        $return_arr = [
            'id' => $this->id,
            'category' => $this->category ? new CategoryDetailsResource($this->category) : null,
            'sub_category' => $this->sub_category ? new SubCategoryDetailsResource($this->sub_category) : null,
            'child_category' => $this->child_category ? new ChildCategoryDetailsResource($this->child_category) : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'unit' => $this->unit,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'is_featured' => $this->is_featured,
            'image' => $this->image ? ImageModifier::ImageUrl($this->image) : '',
            'view' => $this->view,
            'sold_count' => $this->sold_count,
            'status' => $this->status,
            'is_published' => $this->is_published,
            'allocate_staff'=> $this->allocate_staff,
            'created_at' => $this->created_at,
            'total_reviews' => $this->reviews()->count(),
            'average_rating' => number_format($this->reviews()->avg('rating') ?? 0, 1),
            'provider' => new ProviderShortInfoResource($this->provider),
            'admin' => new AdminResource($this->admin),
           
        ];
        
       $offers=$this->offer_service()->get();
        foreach($offers as $offer)
        {
            $offer_id=$offer?->offer_id;
           
            $active=Offer::find($offer_id);
        
            if($active->status==1 && strtotime($active->expires_at)>time())
            {
                
                $discount_price=$offer->discount_price;
                $return_arr['discount_price'] = $discount_price;

            }
          
                
        }
       
        if($this->disable_staff=='1')
        {
           
            if($this->staffs_id)
            {
                
                $staffs_ids = array_map('intval', explode(',', $this->staffs_id));

                // Remove any 0 values
                $staffs_ids = array_filter($staffs_ids, fn($id) => $id > 0);

                // Re-index the array to ensure there are no gaps in the keys
                $staffs_ids = array_values($staffs_ids);
                
                $provider=$this->provider;
                
                if ($provider) {
                    $staffs = $provider->providerStaff()?->whereIn('id', $staffs_ids)->get();
        
                    if ($staffs) {
                        $return_arr['staffs'] = StaffResource::collection($staffs);
                    } else {
                        $return_arr['staffs'] = [];
                    }
                } else {
                    $admin=$this->admin;
                    if ($admin) {
                        
                        $staffs = $admin->staff()?->whereIn('id', $staffs_ids)->get();
            
                        if ($staffs) {
                            $return_arr['staffs'] = StaffResource::collection($staffs);
                        } else {
                            $return_arr['staffs'] = [];
                        }
                    }
                }

           }
            else{
               
                if($this->provider?->providerStaff)
                {
                   
                $return_arr['staffs'] =  StaffResource::collection($this->provider->providerStaff);
                return  $return_arr;
                }
                else if($this->admin?->staff)
                {
                    $return_arr['staffs'] =  StaffResource::collection($this->admin->staff);
                    return  $return_arr;
                }

            }
        }
      
           
        return $return_arr;
        
}
}
