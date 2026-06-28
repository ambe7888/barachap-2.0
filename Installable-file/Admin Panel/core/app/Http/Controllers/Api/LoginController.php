<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function login(Request $request)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|string|max:191',
            'password' => 'required|string',
            'user_type' => 'required|string',
        ]);

        $loginType = filter_var($validatedData['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::select('id','first_name', 'last_name', 'email', 'phone', 'email_verified', 'type', 'username', 'password')
            ->where([$loginType => $validatedData['email']])
            ->first();

        // Check if user
        if (empty($user)) {
            return response()->json([
                'message' => __('User not found.')
            ], 404);
        }

        $user_type = (int)$request->input('user_type');
        if (!$user_type == $user->type) {
            return response()->json([
                'message' => __('User not found'),
            ], 404);
        }

//        // Check if the user account has been deleted
//        if ($user && $user->account_deactivates->isNotEmpty()) {
//            $accountDeactivate = $user->account_deactivates->first();
//            if ($accountDeactivate->status === 1) {
//                return response()->json([
//                    'success' => false,
//                    'message' => __('Your account has been deleted.'),
//                    'status' => __('account-deleted'),
//                ], 403);
//            }
//        }

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid email/username or password.')
            ], 401);
        }

        // Generate API token for the user
        $token = $user->createToken(Str::slug(get_static_option('site_title', 'prohandy')) . 'api_keys')->plainTextToken;

        $email_verify_enable_disable = get_static_option('user_email_verify_enable_disable');
        $status = false;
        if (!empty($email_verify_enable_disable)){
            $status = true;
        }else{
            $status = false;
        }
        $userData = $user->only(['id', 'first_name', 'last_name', 'email', 'phone', 'email_verified', 'type', 'username']);
        // Return success response with user and token
        return response()->json([
            'user' => $userData,
            'token' => $token,
            'verify_enabled' => $status,
        ]);
    }

}
