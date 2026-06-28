<?php

namespace App\Http\Controllers\Api\Provider;

use App\Actions\Media\MediaHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserServiceCategory;
use App\Models\UserServiceLocation;
use App\Models\UserSocialInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{

    public function serviceLocationUpdate(Request $request)
    {

        $user = auth('sanctum')->user();
        // Validate the request data
        $request->validate([
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'post_code' => 'required|string|max:10',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Retrieve or create the user's service location
        UserServiceLocation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'address' => $request->address,
                'post_code' => $request->post_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json(['message' => __('Service Area Added Successfully')]);

    }

}
