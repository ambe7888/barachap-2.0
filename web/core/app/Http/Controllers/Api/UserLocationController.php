<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserLocationResource;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLocationController extends Controller
{

    public function user_all_multiple_location(Request $request){

        $title = $request->input('title');
        $type = $request->input('type');
        $client_id = Auth::guard('sanctum')->user()->id;

        $query = UserLocation::with('state', 'city', 'area')->where('user_id', $client_id);

        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        if ($type !== null) {
            $query->where('type', intval($type));
        }


        $all_locations = $query->latest()->paginate(10);

        if ($all_locations->isNotEmpty()) {
            return response()->json([
                'all_locations' => UserLocationResource::collection($all_locations->items()),
                'pagination' => [
                    'total' => $all_locations->total(),
                    'count' => $all_locations->count(),
                    'per_page' => $all_locations->perPage(),
                    'current_page' => $all_locations->currentPage(),
                    'last_page' => $all_locations->lastPage(),
                    'next_page_url' => $all_locations->nextPageUrl(),
                    'prev_page_url' => $all_locations->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Address Not Available'),
        ], 200);

    }

    public function user_multiple_location_create(Request $request)
    {

        if (!auth('sanctum')->check()){
            return response()->json([
                'error' => __('Please login first')
            ], 403);
        }

        $user = auth('sanctum')->user();
        $user_id = $user->id;

        // Validate the request data
        $validatedData = $request->validate([
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'phone' => 'required|max:191',
            'emergency_phone' => 'nullable|max:20',
            'post_code' => 'nullable|max:10',
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
                'post_code' => $validatedData['post_code'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'emergency_phone' => $validatedData['emergency_phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
                'title' => $validatedData['title'] ?? null,
                'type' => $validatedData['type'] ?? 0,
            ]);
            return response()->json([
                'message' => __('Address Added Successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => __('An error occurred while updating the profile.')
            ], 500);
        }
    }

    public function user_multiple_location_edit(Request $request, $id=null)
    {
        if (!auth('sanctum')->check()){
            return response()->json([
                'error' => __('Please Login First.')
            ], 200);
        }

        $user = auth('sanctum')->user();
        $user_id = $user->id;

        // Validate the request data
        $validatedData = $request->validate([
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'phone' => 'required|max:191',
            'emergency_phone' => 'nullable|max:20',
            'post_code' => 'nullable|max:10',
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
                return response()->json([
                    'error' => __('Location not found')
                ], 200);
            }

            // update user multiple locations
            $location->update([
                'state_id' => $validatedData['state_id'] ?? null,
                'city_id' => $validatedData['city_id'] ?? null,
                'area_id' => $validatedData['area_id'] ?? null,
                'post_code' => $validatedData['post_code'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'emergency_phone' => $validatedData['emergency_phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
                'title' => $validatedData['title'] ?? null,
                'type' => $validatedData['type'] ?? 0,
            ]);

            return response()->json([
                'message' => __('Address updated Successfully')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => __('An error occurred while updating the profile.')
            ], 500);
        }
    }

    public function user_multiple_location_delete(Request $request, $id=null)
    {
        if (!auth('sanctum')->check()){
            return response()->json([
                'error' => __('Please Login First.')
            ], 200);
        }

        $user_id = auth('sanctum')->user()->id;

        try {
            $location = UserLocation::where('user_id', $user_id)
                ->where('id', $id)
                ->first();

            if (!$location) {
                return response()->json([
                    'error' => __('Location not found')
                ], 200);
            }
            $location->delete();

            return response()->json([
                'message' => __('Address Delete Successfully')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => __('An error occurred while the delete.')
            ], 500);
        }
    }


}
