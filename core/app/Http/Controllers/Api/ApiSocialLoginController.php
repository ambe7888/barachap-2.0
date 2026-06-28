<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserSocialInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiSocialLoginController extends Controller
{
    private function ditectSource($source){
        return match($source){
            "google" => "google_id",
            "facebook" => "facebook_id",
            "apple" => "apple_id",
        };
    }

    //social login
    public function social_login(Request $request)
    {
        $authorization = $request->header('secretKey');
        $secret_key = '$2y$10$GlEhJtlTAqv2rvQd2llgPeGGV8RT2Yap844OSazHfHlbU.0bvVTPm';

        if ($authorization !== $secret_key) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $requestData = $request->validate([
            'source' => 'required',
            'is_go_fb_ap_id' => 'required',
            'user_type' => 'required',
        ]);

        $user_type = $requestData['user_type'];

        if (!empty($request->email)){
            $user = User::select('users.id', 'users.email', 'users.username', 'users.type', 'users.first_name', 'users.last_name')
                ->leftJoin('user_social_infos', 'users.id', '=', 'user_social_infos.user_id')
                ->where('users.email', $request->email)
                ->where('type', $user_type)
                ->first();
        }else{
            $user = User::select('users.id', 'users.email', 'users.username', 'users.type', 'users.first_name', 'users.last_name')
                ->leftJoin('user_social_infos', 'users.id', '=', 'user_social_infos.user_id')
                ->where('user_social_infos.' . $this->ditectSource($requestData['source']), $requestData['is_go_fb_ap_id'])
                ->where('type', $user_type)
                ->first();

            if (empty($user)){
                return response()->json([
                    'message' => __('User not found'),
                ], 404);
            }

        }

        if (is_null($user)) {
            $nextValidation = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|unique:users',
                'user_type' => 'required',
            ]);

            // create username
            $username = strtolower($nextValidation['firstname'] . $nextValidation['lastname']) . rand(11111111,99999999);
            $checkUser = User::where('username', $username)->first();
            if (is_null($checkUser)) {
                $username .= rand(11111111,99999999);
            }

            $user = User::create([
                'first_name' => $nextValidation['firstname'] ?? '',
                'last_name' => $nextValidation['lastname'] ?? '',
                'email' => $nextValidation['email'],
                'username' => $username,
                'password' => Hash::make(Str::random(8)),
                'type' => (int)$user_type ?? 1,
                'terms_condition' => 1,
                'email_verified' => 1,
            ]);

            // Create a new record
            UserSocialInfo::create([
                'user_id' => $user->id,
                'apple_id' => $requestData['source'] === 'google' ? $requestData['is_go_fb_ap_id'] : null,
                'google_id' => $requestData['source'] === 'facebook' ? $requestData['is_go_fb_ap_id'] : null,
                'facebook_id' => $requestData['source'] === 'apple' ? $requestData['is_go_fb_ap_id'] : null,
            ]);

            // if exists wallet module
            if($user->type == 0){
                UserBalance::create([
                    'user_id' => $user->id,
                    'available_balance' => 0,
                    'total_earnings' => 0,
                    'total_withdrawn' => 0,
                    'total_refunds' => 0,
                ]);
            }
        }



        // Assuming social IDs like apple_id, facebook_id are stored in user_social_infos table
        $socialInfo = UserSocialInfo::where('user_id', $user->id)->first();

       // If social info does not exist, create a new entry
        if (!$socialInfo) {
            $socialInfo = new UserSocialInfo();
            $socialInfo->user_id = $user->id;
        }

      // Check if the existing value for the social ID matches the incoming value
        $sourceColumn = $this->ditectSource($requestData['source']);
        if ($socialInfo->{$sourceColumn} !== $requestData['is_go_fb_ap_id']) {
            // Update the social ID
            $socialInfo->{$sourceColumn} = $requestData['is_go_fb_ap_id'];
            $socialInfo->save();
        }


        $token = $user->createToken(Str::slug(get_static_option('site_title', 'prohandy')) . 'api_keys')->plainTextToken;



        return response()->json([
            'user_details' => $user,
            'token' => $token,
        ]);
    }
}
