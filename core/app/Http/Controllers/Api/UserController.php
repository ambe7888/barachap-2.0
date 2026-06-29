<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\SMSGateway\app\Http\Traits\OtpGlobalTrait;
use Modules\SMSGateway\app\Models\UserOtp;

class UserController extends Controller
{

    use OtpGlobalTrait;

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => __('Logged out successfully'),
        ]);
    }


    public function sendOTP(Request $request)
    {

        $request->validate([
            'email' => 'required',
        ]);

        $otp_code = sprintf("%d", random_int(1234, 9999));
        $user_email = User::where('email', $request->email)->first();

        if (!is_null($user_email)) {
            try {
                $message = get_static_option('user_email_verify_message');
                $message = str_replace(["@name", "@email_verify_tokn"],[$user_email->name, $otp_code],$message);
                $subject = get_static_option('user_email_verify_subject');

                Mail::to($user_email->email)->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {
                return response()->json([
                    'message' => __($e->getMessage()),
                ]);
            }

            return response()->json([
                'email' => $request->email,
                'otp' => $otp_code,
            ], 201);

        } else {
            return response()->json([
                'message' => __('Email Does not Exists'),
            ]);
        }

    }

    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        $user = User::select('id','password')->where('id', auth('sanctum')->user()->id)->first();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => __('Current Password is Wrong'),
            ]);
        }

        User::where('id',auth('sanctum')->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'current_password' => $request->current_password,
            'new_password' => $request->new_password,
        ], 201);
    }

    public function resetPassword(Request $request)
    {
        if ($request->reset_password_verify != 1) {
            // Initial password reset request (send OTP)
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            // Generate OTP (6-digit random number)
            $otp = rand(100000, 999999);

            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => __('Email not found.'),
                ], 404);
            }

            // Save OTP and its expiration time
            $user->update([
                'email_verify_token' => $otp,
            ]);

            // Send OTP via email
            $message = __('Hello,') . '<br>'
                . __('You have requested to reset your password. Please use the following OTP to verify your request:')
                . '<br>' . __('Your OTP is: ') . $otp . '<br>';

            try {
                Mail::to($request->email)->send(new BasicMail([
                    'subject' => __('Password Reset OTP'),
                    'message' => $message,
                ]));

                return response()->json([
                    'message' => __('A verification email with an OTP has been sent.'),
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => __('Failed to send email. Please try again later.'),
                ], 500);
            }

        } else {
            // Password reset verification (user provides OTP and new password)
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required',
                'password' => 'nullable|min:6',
            ]);

            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => __('Email not found.'),
                ], 404);
            }

            // Check if the OTP matches and is not expired
            if ($user->email_verify_token !== $request->otp) {
                return response()->json([
                    'message' => __('Invalid OTP.'),
                ], 400);
            }

            // Update user's password
            $user->update([
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'email_verify_token' => null,
            ]);

            return response()->json([
                'message' => __('Password has been successfully reset.'),
            ]);
        }
    }

    public function changeUserEmail(Request $request)
    {
        // Validate the new email
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

            // Generate a verification token
            $verificationToken = mt_rand(100000, 999999); // Generates a random 6-digit number
            $user = Auth::guard('sanctum')->user();

            // Store the token temporarily for verification
            if ($request->email_verified == 1) {
                $request->validate([
                    'email' => 'required|email|unique:users,email',
                    'otp' => 'required',
                ]);

                // Check if the provided OTP (token) matches the stored token
                if ($user->email_verify_token !== $request->otp) {
                    return response()->json([
                        'message' => __('Invalid OTP. Please check the OTP sent to your email.'),
                    ], 400);
                }

                $user->update([
                    'email_verify_token' => $verificationToken,
                    'email' => $request->email,
                    'email_verified' => 1,
                ]);

                return response()->json([
                    'message' => __('Your email has been successfully updated and verified.'),
                ]);
            }

        try {

            $user->update([
                'email_verify_token' => $verificationToken,
            ]);

            $message = __('Hello,') . '<br>'
                . __('You have requested to change your email. Please use the following OTP to verify your new email:')
                . '<br>' . __('Your OTP is: ') . $verificationToken . '<br>'
                . __('Please verify your email change request.');
            Mail::to($request->email)->send(new BasicMail([
                'subject' => __('Email Change Verification'),
                'message' => $message
            ]));
            return response()->json([
                'message' => __('A verification email has been sent to your new email address.'),
            ]);
        } catch (\Exception $e) {}
    }

    public function changePhoneNumber(Request $request)
    {
        if ($request->input('firebase_verified') == 1) {
            $user = auth('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'message' => __('User Not Found.'),
                ], 400);
            }
            $user->update([
                'otp_verified' => 1,
                'phone' => $request->input('phone'),
            ]);
            return response()->json([
                'message' => __('Phone Number Changed Successfully.'),
            ]);
        }

        try {
            $validated = $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'regex:/^\+?[0-9]{7,15}$/',
                ],
                'remember' => 'nullable|boolean'
            ], [
                'phone.required' => __('Phone number is required.'),
                'phone.regex' => __('Please provide a valid phone number.'),
            ]);
        } catch (ValidationException $e) {
            // Return only the first validation error message
            return response()->json([
                'message' => $e->validator->errors()->first()
            ], 422);
        }


        $sentOtp = null;

        $otp_verify_status = $request->input('otp_verify_status');

        // otp verify check and update phone number
        if ($otp_verify_status == 1){
            $validated = $request->validate([
                'otp' => 'required|numeric|digits:6',
                'phone' => 'required',
            ]);

            $userOtp = UserOtp::where('otp_code', $validated['otp'])->first();

            if (empty($userOtp)) {
                return response()->json([
                    'message' => __('The OTP code you have entered is not correct'),
                ]);
            }

            $user = User::select('id', 'first_name', 'last_name', 'type', 'email', 'phone', 'username','otp_verified')
                ->where('id', $userOtp->user_id)
                ->first();

            // Check user
            if (!$user) {
                return response()->json([
                    'message' => __('User Not Found.'),
                ], 400);
            }

            if (!now()->isAfter($userOtp->expire_date)) {
                // update user otp verified status
                $user->update([
                    'otp_verified' => 1,
                    'phone' => $validated['phone'],
                ]);

                return response()->json([
                    'message' => __('Phone Number Changed Successfully.'),
                ]);

            } else {
                return response()->json([
                    'message' => __('The OTP code is expired. Apply for new OTP code'),
                ]);
            }

            // generate new otp
        }else{

            try {
                // Generate the OTP and send SMS
                $otp = $this->generateOtp($validated['phone']);
                $sentOtp = $this->sendSms([$validated['phone'], __('Your login OTP: ') . $otp->otp_code, $otp->otp_code], 'otp');

                // Check if the OTP was successfully sent
                if ($sentOtp === true) {
                    return response()->json([
                        'message' => __('OTP has been sent to your mobile number.'),
                        'status'  => 'success',
                    ]);
                } else {
                    $test_client = $this->config();
                    $dbg = 'GW: ' . ($test_client['gateway'] ?? 'null') . ' | Token: ' . substr($test_client['client']['token'] ?? 'NONE', 0, 5);
                    // If the OTP wasn't sent successfully, return an error response
                    return response()->json([
                        'message' => 'Debug -> ' . $dbg,
                        'status'  => 'error',
                    ], 500);
                }

            }catch (\Exception $e) {
                \Log::error('OTP Send Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
                // Handle any other general exceptions
                return response()->json([
                    'message' => __('An unexpected error occurred.') . ' Details: ' . $e->getMessage(),
                    'status' => 'error',
                ], 500);
            }
        }
    }

}
