<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserRegisterLoginInfoResource;
use App\Jobs\SendRegisterUserEmailJob;
use App\Mail\BasicMail;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
       // Validate request data
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users|max:191',
            'password' => 'required|min:6|max:191',
            'terms_conditions' => 'required|boolean',
            'type' => 'required|in:0,1'
        ]);

        $emailParts = explode('@', $validatedData['email']);
        $userName = preg_replace('/[^a-zA-Z0-9_]/', '', $emailParts[0]);
        // Ensure the username is unique
        $originalUserName = $userName; // Store the original username
        $i = 1;
        while (User::where('username', $userName)->exists()) {
            $userName = $originalUserName . '_' . $i;
            $i++;
        }

        $userType = UserTypes::getValue($validatedData['type']);
        $email_verify_tokn = sprintf("%d", random_int(123456, 999999));

        // Create user record
        $user = User::create([
            'username' => $userName,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => $userType,
            'terms_condition' => $validatedData['terms_conditions'] ? 1 : 0,
            'email_verify_token'=> $email_verify_tokn,
            'status' => 1,
        ]);


        // create user balance
        if (!empty($user) && $user->type === 0) {
            UserBalance::create([
                'user_id' => $user->id,
                'available_balance' => 0,
                'total_earnings' => 0,
                'total_withdrawn' => 0,
                'total_refunds' => 0,
            ]);
        }

        //send OTP to user Email
        if($user){
            if (!empty(get_static_option('user_email_verify_enable_disable'))){
                try {
                    Mail::to($user->email)->send(new BasicMail([
                        'subject' =>  __('Otp Email'),
                        'message' => __('Your otp code').' '.$email_verify_tokn
                    ]));
                }
                catch (\Exception $e) {}
            }

            $user_request_password = $request->password;
            // Dispatch job to send welcome email in the background
            dispatch(new SendRegisterUserEmailJob($user,$user_request_password));
        }


        // Check if user creation was successful
        if ($user) {
            $token = $user->createToken(Str::slug(get_static_option('site_title', 'prohandy')) . '_api_keys')->plainTextToken;

            $email_verify_enable_disable = get_static_option('user_email_verify_enable_disable');
            $status = false;
            if (!empty($email_verify_enable_disable)){
                $status = true;
            }else{
                $status = false;
            }

            return response()->json([
                'user' => new UserRegisterLoginInfoResource($user),
                'token' => $token,
                'verify_enabled' => $status,
            ]);
        }

        // Return error response if user creation failed
        return response()->json([
            'message' => __('Something went wrong, please try again.'),
        ], 500);
    }

}
