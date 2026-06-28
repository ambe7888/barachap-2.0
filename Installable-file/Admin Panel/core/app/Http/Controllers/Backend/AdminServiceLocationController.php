<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Admin_service_location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;
use App\Helpers\FlashMsg;

class AdminServiceLocationController extends Controller
{
    public function serviceLocation()
    {
        $states=State::all();
        $serviceLocation=Admin_service_location::with('city','area')->first();
        if($serviceLocation)
        {
            return view('backend.pages.admin.serviceLocation.serviceLocation',compact('states','serviceLocation'));
        }
        $serviceLocation=null;
        return view('backend.pages.admin.serviceLocation.serviceLocation',compact('states','serviceLocation'));
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
    public function serviceLocationUpdate(Request $request)
    {
        
        $admin = Auth::guard('admin')->user();
        // Validate the request data
        $request->validate([
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'post_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Retrieve or create the user's service location
        Admin_service_location::updateOrCreate(
            ['admin_id' => $admin->id],
            [
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'address' => $request->seller_address,
                'post_code' => $request->zipcode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return redirect()->back()->with(FlashMsg::item_new(__('Service location created Successfully')));
    
    

    }
}
