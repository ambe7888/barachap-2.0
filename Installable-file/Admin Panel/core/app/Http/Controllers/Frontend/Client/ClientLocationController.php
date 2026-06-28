<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLocation;
use App\Helpers\FlashMsg;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;
use App\Models\Backend\Category;
use Modules\JobPost\app\Models\JobPost;


class ClientLocationController extends Controller
{
    public function user_all_multiple_location(Request $request){

     
        $client_id = Auth::guard('sanctum')->user()->id;

        $all_locations = UserLocation::with('state', 'city', 'area')->where('user_id', $client_id)->latest()->paginate(10);
        
        return view('frontend.frontend.client.pages.location.all-location', compact('all_locations'));
          
    }

    public function user_multiple_location_map_page(Request $request,$flag=null,$job_id=null)
    {
        $states= State::all();

        return view('frontend.frontend.client.pages.location.map.create',compact('states','flag','job_id'));
    }

    public function user_multiple_location_map_edit_page(Request $request,$id)
    {
        $location = UserLocation::where('user_id', auth('sanctum')->user()->id)->where('id', $id)->first();
        $states= State::all();
        
        return view('frontend.frontend.client.pages.location.map.edit', compact('location','states'));
    }

    public function user_multiple_location_create(Request $request,$flag=null,$job_id=null)
    {
        if($request->isMethod('post')){

            $user = auth('sanctum')->user();
            $user_id = $user->id;

            // Validate the request data
            $validatedData = $request->validate([
                'state_id' => 'nullable|exists:states,id',
                'city_id' => 'nullable|exists:cities,id',
                'area_id' => 'nullable|exists:areas,id',
                'phone' => 'required|max:191',
                'emergency_phone' => 'nullable|max:20',
                'zipcode' => 'nullable|max:10',
                'address' => 'nullable|max:191',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'type' => 'required',
                'title' => 'required',
            ]);


            try {
                // Store user multiple locations
                UserLocation::create([
                    'user_id' => $user_id,
                    'state_id' => $validatedData['state_id'] ?? null,
                    'city_id' => $validatedData['city_id'] ?? null,
                    'area_id' => $validatedData['area_id'] ?? null,
                    'post_code' => $validatedData['zipcode'] ?? null,
                    'phone' => $validatedData['phone'] ?? null,
                    'emergency_phone' => $validatedData['emergency_phone'] ?? null,
                    'address' => $validatedData['address'] ?? null,
                    'latitude' => $validatedData['latitude'] ?? null,
                    'longitude' => $validatedData['longitude'] ?? null,
                    'title' => $validatedData['title'] ?? null,
                    'type' => $validatedData['type'] ?? 0,
                ]);

                 
                if($request->flag == 'from-job')
                {
                    $category=Category::where('status',1)->get();
                    $user_location=UserLocation::where('user_id',auth('sanctum')->user()->id)->get();
                    return view('jobpost::frontend.client.create-job',compact('category','user_location'))->with(FlashMsg::item_new(__('Location Created Successfully')));
                }
                else if($request->flag == 'from-job-edit')
                {
                    $client_id = auth('sanctum')->user()->id;
                    $job = JobPost::where('id', $request->job_id)->where('client_id', $client_id)->with('job_location')->first();
                   
                    $category=Category::where('status',1)->get();
                    $user_location=UserLocation::where('user_id',auth('sanctum')->user()->id)->get();

                    return view('jobpost::frontend.client.edit-job', compact('job','category','user_location'))->with(FlashMsg::item_new(__('Location Created Successfully')));
                }

                return back()->with(FlashMsg::item_new(__('Address Added Successfully')));
                

            } catch (\Exception $e) {

                return back()->with(FlashMsg::item_delete(__('An error occurred while creating the location.')));
            
            }
        }

        $states= State::all();
        $job_id=$request->job_id;
        $flag=$request->flag;

        return view('frontend.frontend.client.pages.location.create',compact('states','flag','job_id'));
    }

    public function user_multiple_location_edit(Request $request, $id=null)
    {
        if($request->isMethod('post')){
            $user = auth('sanctum')->user();
            $user_id = $user->id;

            // Validate the request data
            $validatedData = $request->validate([
                'state_id' => 'nullable|exists:states,id',
                'city_id' => 'nullable|exists:cities,id',
                'area_id' => 'nullable|exists:areas,id',
                'phone' => 'required|max:191',
                'emergency_phone' => 'nullable|max:20',
                'zipcode' => 'nullable|max:10',
                'address' => 'nullable|max:191',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'type' => 'required',
                'title' => 'required',
            ]);


            try {
                $location = UserLocation::where('user_id', $user_id)
                    ->where('id', $id)
                    ->first();

                if (!$location) {
                    return back()->with(FlashMsg::item_delete(__('Location not found')));
                }

                // update user multiple locations
                $location->update([
                    'state_id' => $validatedData['state_id'] ?? null,
                    'city_id' => $validatedData['city_id'] ?? null,
                    'area_id' => $validatedData['area_id'] ?? null,
                    'post_code' => $validatedData['zipcode'] ?? null,
                    'phone' => $validatedData['phone'] ?? null,
                    'emergency_phone' => $validatedData['emergency_phone'] ?? null,
                    'address' => $validatedData['address'] ?? null,
                    'latitude' => $validatedData['latitude'] ?? null,
                    'longitude' => $validatedData['longitude'] ?? null,
                    'title' => $validatedData['title'] ?? null,
                    'type' => $validatedData['type'] ?? 0,
                ]);

                return back()->with(FlashMsg::item_new(__('Address updated Successfully')));

            } catch (\Exception $e) {

                return back()->with(FlashMsg::item_delete(__('An error occurred while updating the profile.')));
            }
        }
        $location = UserLocation::where('user_id', auth('sanctum')->user()->id)->where('id', $id)->first();
        $states= State::all();
        
        return view('frontend.frontend.client.pages.location.edit', compact('location','states'));
    }

    public function user_multiple_location_delete(Request $request, $id=null)
    {
       
        $user_id = auth('sanctum')->user()->id;

        try {
            $location = UserLocation::where('user_id', $user_id)
                ->where('id', $id)
                ->first();

            if (!$location) {

                return back()->with(FlashMsg::item_delete(__('Location not found')));
                
            }
            $location->delete();

            return back()->with(FlashMsg::item_new(__('Address Delete Successfully')));
           

        } catch (\Exception $e) {

            return back()->with(FlashMsg::item_delete(__('An error occurred while the delete.')));
            
        }
    }

    public function getCity($stateId)
    {
       
        $cities = City::where('state_id', $stateId)->get();

        return response()->json([
            'cities' => $cities,
        ]);
    }
    public function getArea($cityId,$stateId)
    {
        $areas = Area::where('city_id', $cityId)->where('state_id',$stateId)->get();
        return response()->json([
            'areas' => $areas,
        ]);
    }

}
