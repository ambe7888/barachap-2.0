<?php

namespace App\Http\Resources;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\Services\ServiceLocationResource;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminWithStaffResource extends JsonResource
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
        
        $return_arr = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => ImageModifier::ImageUrl($this->image),
            'service_location'=>new ServiceLocationResource($this->adminServiceLocation),
        ];
        // Paginate the staff data with a limit of 10 per page
        //$staffs = $this->adminStaff()->paginate(10);
        if($this->disable_staff=='1')
        {
           
            if($this->staffs_id)
            {
                $staffs_ids = array_map('intval', explode(',', $this->staffs_id));
                // Remove any 0 values
                $staffs_ids = array_filter($staffs_ids, fn($id) => $id > 0);

                // Re-index the array to ensure there are no gaps in the keys
                $staffs_ids = array_values($staffs_ids);
                $staffs = $this->staff?->whereIn('id', $staffs_ids);
                if($staffs)
                {
                    $return_arr['staffs'] = StaffResource::collection($staffs);
                    return  $return_arr;
                }
                else
                {
                    $return_arr['staffs']=[];
                }
                
               
            }
            else{
                if($this->staff)
                {
                $return_arr['staffs'] = StaffResource::collection($this->staff);
                return  $return_arr;
                }
                else
                {
                    $return_arr['staffs']=[];
                }

            }

        }

        return  $return_arr;
    }
}
