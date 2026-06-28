<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\IdentityVerificationResource;
use App\Mail\BasicMail;
use App\Models\AccountDeactivate;
use App\Models\Backend\IdentityVerification;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;

class AccountSettingController extends Controller
{

    public function  userProfileVerifyInfo(){
        $user_id = Auth::guard('sanctum')->user()->id;
        $user_verify_info = IdentityVerification::where('user_id', $user_id)->first();
        return response()->json([
            'user_verify_info' => new IdentityVerificationResource($user_verify_info),
        ]);
    }

    public function userProfileVerify(Request $request){

        $request->validate([
            'identification_type' => 'required|max:191',
            'identification_number' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
        ]);

        if ($request->file('front_document') || $request->file('back_document')){
            $request->validate([
                'front_document' => 'required|file|mimes:jpg,png,jpeg,webp,pdf|max:10240',
                'back_document' => 'required|file|mimes:jpg,png,jpeg,webp,pdf|max:10240',
            ]);
        }

        if($front_document = $request->file('front_document')){
            $front_imageName = time().'-'.uniqid().'.'.$front_document->getClientOriginalExtension();
            $front_document->move('assets/uploads/verification',$front_imageName);
        }

        if($back_document = $request->file('back_document')){
            $back_imageName = time().'-'.uniqid().'.'.$back_document->getClientOriginalExtension();
            $back_document->move('assets/uploads/verification',$back_imageName);
        }

        $user = Auth::guard('sanctum')->user()->id;
        $old_document = IdentityVerification::where('user_id', $user)->first();

        if (!empty($old_document) && $old_document->status === 1){
            return response()->json([
                'msg' => __('The verification information is already verified.')
            ]);
        }


        if(is_null($old_document)){
            IdentityVerification::create([
                'user_id' => $user,
                'front_document' => $front_imageName,
                'back_document' => $back_imageName,
                'identification_type' => $request->identification_type,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'identification_number' => $request->identification_number,
            ]);
        }else{
            IdentityVerification::where('user_id', $user)->update([
                'user_id' => $user,
                'front_document' => $front_imageName ?? $old_document->front_document,
                'back_document' => $back_imageName ?? $old_document->back_document,
                'identification_type' => $request->identification_type,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'identification_number' => $request->identification_number,
                'status' => 0,
            ]);
        }

        $user = Auth::guard('sanctum')->user();

        try {
            $subject = get_static_option('user_identity_verification_subject') ?? __('User Verification Request');
            $message = get_static_option('admin_user_identity_verification_message') ?? __('User Name: @name , User Email: @email Verification Request');
            // Replace placeholders with actual data
            $message = str_replace(['@name', '@email'], [$user->fullname, $user->email], $message);
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        return response()->json([
            'msg' => __('Verify Info Updated Success---')
        ]);
    }

    public function accountDeactive(Request $request)
    {
        $request->validate([
            'reason_id' => 'required',
            'description' => 'required|max:150',
        ]);

        $userId = Auth::guard('sanctum')->user()->id;
        $accountDeactivation = AccountDeactivate::where('user_id', $userId)->first();
        if ($accountDeactivation) {
            return response()->json([
                'message' => __('Your account is already deactivated.'),
            ]);
        }

        //Deactivate Account
        AccountDeactivate::create([
            'user_id' => $userId,
            'reason_id' => $request['reason_id'],
            'description' => $request['description'],
            'status' => 0,
            'account_status' => 0,
        ]);

        // if user account deactivate if type 1 all job post is_published 0 and status 0
        $user_type = Auth::guard('sanctum')->user()->type;
        try {
            if ($user_type === 1){
                JobPost::where('client_id', $userId)->update([
                    'is_published' => 0,
                    'status' => 0,
                ]);
            }else{
                Service::where('provider_id', $userId)->update([
                    'is_published' => 0,
                    'status' => 0,
                ]);
            }
        }catch (\Exception $e) {}

        return response()->json([
            'message' => __('Your Account Successfully Deactivate'),
        ]);

    }

    public function accountActive()
    {
        $user_id = Auth::guard('sanctum')->user()->id;
        $account_details = AccountDeactivate::where('user_id', $user_id)->first();

        if (!empty($account_details)){
            $account_details->delete();

            // if user account deactivate if type 1 all job post is_published 0 and status 0
            $user_type = Auth::guard('sanctum')->user()->type;
            try {
                if ($user_type === 1){
                    JobPost::where('client_id', $user_id)->update([
                        'is_published' => 0,
                        'status' => 0,
                    ]);
                }else{
                    Service::where('provider_id', $user_id)->update([
                        'is_published' => 0,
                        'status' => 0,
                    ]);
                }
            }catch (\Exception $e) {}
        }


        return response()->json([
            'message' => __('Your Account Successfully Active'),
        ]);
    }

    public function accountDelete(Request $request)
    {

            $request->validate([
                'reason_id' => 'required',
                'description' => 'required|min:10',
                'password' => 'required',
            ]);

            $user = Auth::guard('sanctum')->user();
            $user_id = $user?->id;

        // Verify the provided password matches the current user's password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('The provided password is incorrect.'),
            ], 403);
        }


        $account_deactivate= AccountDeactivate::where('user_id', $user_id)->first();
        if($account_deactivate){
            $account_deactivate->reason= $request['reason'];
            $account_deactivate->description= $request['description'];
            $account_deactivate->status = 1;
            $account_deactivate->account_status = 1;
            $account_deactivate->save();
        }
        else
        {
            AccountDeactivate::create([
                'user_id' => $user_id,
                'reason' => $request['reason'],
                'description' => $request['description'],
                'status' => 1,
                'account_status' => 1,
            ]);
        }


        User::find($user_id)?->delete();
        // Revoke the user's API token
        $user?->tokens()->delete();


        return response()->json([
            'message' => __('Your Account Has Been Deleted Successfully'),
            'logout' => true,
        ]);

    }

}
