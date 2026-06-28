<?php

namespace App\Http\Services;


use App\Actions\Media\MediaHelper;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffManageService
{
    public function create_staff(Request $request): Staff
    {
        $provider = Auth::guard('sanctum')->user();

        if($request->file('image')){
            MediaHelper::insert_media_image($request,'web','image');
            $image_id = DB::getPdo()->lastInsertId();
        }

        // Create a new staff instance
        $staff = Staff::create([
            'provider_id' => $provider->provider_id,
            'admin_id' => $request->admin_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $image_id ?? null,
            'about' => $request->about,
            'status' => 1,
        ]);

        return $staff;
    }

    public function edit_staff(Request $request, $serviceId): Staff
    {
        $provider = Auth::guard('sanctum')->user();
        $service = Staff::find($serviceId);

        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        if($request->file('image')){
            MediaHelper::insert_media_image($request,'web','image');
            $image_id = DB::getPdo()->lastInsertId();
        }
        $old_img = $service->image;

        // Create a new Service instance
        $service->update([
            'provider_id' => $provider->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'unit' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'image' => $image_id ?? $old_img,
        ]);

        return $service;
    }
}
