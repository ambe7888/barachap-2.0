<?php

namespace Modules\SMSGateway\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\SMSGateway\app\Http\Traits\OtpGlobalTrait;
use Modules\SMSGateway\app\Models\SmsGateway;
use Modules\SMSGateway\app\Models\UserOtp;

class UserOtpManageController extends Controller
{
    use OtpGlobalTrait;

    public function __construct()
    {
        abort_if(empty(get_static_option('otp_login_status')), 404);
    }

    public function userPhoneNumberCheck(Request $request)
    {
        $phone = User::where('phone', $request->phone)->first();
        if(!empty($phone) && $phone->phone == $request->phone){
            $status = 'available';
            $msg = __('Phone number is correct');
        }else{
            $status = 'not_available';
            $msg = __('Sorry! Phone number is not correct');
        }
        return response()->json([
            'status'=>$status,
            'msg'=>$msg,
            'phone'=>$phone,
        ]);
    }
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^[0-9+]+$/|exists:users,phone',
            'remember' => 'nullable'
        ], ['phone.exists' => __('No record found for this phone number.')]);

        $sentOtp = null;

        try {
            $otp = $this->generateOtp($validated['phone']);
            $sentOtp = $this->sendSms([$validated['phone'], __('Your login OTP: ') . $otp->otp_code, $otp->otp_code], 'otp');

            if ($sentOtp){
                // Return a success response
                return response()->json([
                    'message' => __('OTP has been sent on Your Mobile Number.'),
                ]);
            }

        } catch (\Exception $exception) {
            if ($exception->getCode() == 20003){
                return response()->json([
                    'message' => __('OTP login in unavailable right now.'),
                ]);
            }
        }

    }


    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required',
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|regex:/^\+?[0-9]{7,15}$/',
            'remember' => 'nullable'
        ]);

        $userOtp = UserOtp::where('otp_code', $validated['otp'])->first();
        if (empty($userOtp)) {
             return response()->json([
                 'message' => __('The OTP code you have entered is not correct'),
             ]);
        }


        $user = User::select('id', 'first_name', 'last_name', 'type', 'email', 'phone', 'username','otp_verified')
            ->where('id', $userOtp->user_id)
            ->where('phone', $request->phone)
            ->first();

        // Check user
        if (!$user) {
            return response()->json([
                'message' => __('User Not Found.'),
            ], 400);
        }

        $user_type = (int)$request->input('user_type');
        if (!$user_type == $user->type) {
            return response()->json([
                'message' => __('User not found'),
            ], 404);
        }

        if (!now()->isAfter($userOtp->expire_date)) {
            // update user otp verified status
            $user->update(['otp_verified' => 1]);
            // Generate a new API token
            $token = $user->createToken('prohandy____api_keys' . rand(111111, 999999))->plainTextToken;

            return response()->json([
                'message' => __('OTP verified successfully'),
                'users' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'message' => __('The OTP code is expired. Apply for new OTP code'),
            ]);
        }
    }

    public function resendOtp(Request $request)
    {

        try {
            $validated = $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'regex:/^\+?[0-9]{7,15}$/',
                    'exists:users,phone'
                ],
                'remember' => 'nullable|boolean'
            ], [
                'phone.required' => __('Phone number is required.'),
                'phone.regex' => __('Please provide a valid phone number.'),
                'phone.exists' => __('No record found for this phone number.'),
            ]);
        } catch (ValidationException $e) {
            // Return only the first validation error message
            return response()->json([
                'message' => $e->validator->errors()->first()
            ], 422);
        }

        $user_details = User::where('phone', $request->phone)->first();

        // Check if user details were found
        if (empty($user_details)) {
            return response()->json([
                'message' => __('No user found with the provided phone number. Please check the number and try again.'),
            ], 404);
        }

       $sms_settings = SMSGateway::active()->first();
       $user_otp_expire_time = $sms_settings->otp_expire_time;

        // Check if the OTP has expired
        $user_otp = UserOtp::where('user_id', $user_details->id)->latest()->first();

        // Ensure otp_expire_at is a Carbon instance
        $otpExpireAt = Carbon::parse($user_otp->expire_date);
        if ($user_otp_expire_time == '30') {
            $timeUnit = 'seconds';
            // Assuming 30 seconds as a fixed example
            $otpExpireAt = $otpExpireAt->addSeconds(30);
        } else {
            $timeUnit = 'minutes';
            $otpExpireAt = $otpExpireAt->addMinutes((float)$user_otp_expire_time);
        }

        if (now()->isBefore($otpExpireAt)) {
            return response()->json([
                'message' => __('OTP already sent. Please try again after ' . $user_otp_expire_time . ' ' . $timeUnit . '.')
            ]);
        }

        if (!empty($user_details) && !empty($user_otp)){
            if (now()->isAfter($user_otp->expire_date)) {
                try {
                    $otp = $this->generateOtp($validated['phone']);
                    $sentOtp = $this->sendSms([$validated['phone'], __('Your login OTP: ') . $otp->otp_code, $otp->otp_code], 'otp');
                    if ($sentOtp){
                        // Return a success response
                        return response()->json([
                            'message' => __('OTP reset successful. A new code has been sent to your phone.'),
                        ]);
                    }
                } catch (\Exception $exception) {
                    if ($exception->getCode() == 20003){
                        return response()->json([
                            'message' => __('You can request a new OTP after the countdown has finished.'),
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'message' => __('You can request a new OTP after the countdown has finished'),
                ]);
            }
        }

        return response()->json([
            'message' => __('Something went wrong'),
        ]);

    }

}
