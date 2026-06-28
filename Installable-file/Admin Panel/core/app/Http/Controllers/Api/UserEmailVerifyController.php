<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserEmailVerifyController extends Controller
{
    // send otp
    public function sendOTPSuccess(Request $request)
    {

        $request->validate([
            'user_id' => 'required|integer',
            'email_verified' => 'required|integer',
        ]);

        if(!in_array($request->email_verified,[0,1])){
            return response()->json([
                'message' => __('email verify code must have to be 1 or 0'),
            ]);
        }

        $user = User::where('id', $request->user_id)->update([
            'email_verified' =>  $request->email_verified
        ]);

        if(is_null($user)){
            return response()->json([
                'message' => __('Something went wrong, please try after sometime,'),
            ]);
        }

        return response()->json([
            'message' => __('Email Verify Success'),
        ], 201);
    }
}
