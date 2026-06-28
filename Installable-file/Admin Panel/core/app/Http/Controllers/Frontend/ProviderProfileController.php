<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\AccountDeactivate;
use App\Models\Backend\Reason;

class ProviderProfileController extends Controller
{

    public function userProfile(){
        return view('frontend.frontend.provider.pages.profile.edit-profile');
    }
    public function userProfileUpdate(Request $request){

        $this->validate($request,[
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'date_of_birth' => 'nullable|date',
            'profile_image' => 'nullable|string',
        ]);

        $video_url = !empty($request->video_url) ? getYoutubeEmbedUrl($request->video_url) : null;
        $galleryImagesArray = array_map('trim', explode(',', $request->gallery_images));
        $gallery_images = is_array($galleryImagesArray) ? implode('|', $galleryImagesArray) : $galleryImagesArray;
        //dd($request->profile_image);
        $image=$request->profile_image;
        User::where("id",Auth::user()->id)->update([
            'first_name'=>$request->first_name,
            'last_name' =>$request->last_name,
            'email' => $request->email,
            'image' => $image,
            'date_of_birth' => $request->date_of_birth,
            'about' => $request->about,
            'video_url' => $video_url,
            'store_images' => $gallery_images,
        ]);
        return redirect()->back()->with(['msg' => __('Profile Update Success'), 'type' => 'success']);
    }

    public function userPasswordChange(Request $request){

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string'
        ],[
            'old_password.required' => __("Your Old Password is required."),
            'new_password.required' => __("New Password is required."),
            'new_password_confirmation.required' => __("Confirm Your new Password")
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(), // Send all errors
            ]);
        }


        $user = User::findOrFail(Auth::user()->id);

        if (Hash::check($request->old_password ,$user->password)){
            $user->password = Hash::make($request->new_password);
            $user->password_changed_at=Carbon::now();
            $user->save();
            Auth::guard('web')->logout();
            return response()->json([
               'status' => 'success'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'error' =>"Old password is not matched"
         ]);
    }

    public function userLogout(){
        Auth::logout();
        return redirect()->route('user.login')->with(['msg'=>__('You Logged Out !!'),'type'=> 'danger']);
    }


    public function userPassword(){
        return view('frontend.frontend.provider.pages.profile.change-password');
    }

    public function accountDeletePage()
    {
        $reasons=Reason::where('status',1)->get();
        return view('frontend.frontend.provider.pages.profile.account-delete',compact('reasons'));
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
        if (!Hash::check($request->password, $user?->password)) {

            return redirect()->back()->with(['msg'=>__('The provided password is incorrect.'),'type'=> 'danger']);

        }

        $account_deactivate = AccountDeactivate::where('user_id', $user_id)->first();
        if($account_deactivate){
            $account_deactivate->reason_id= $request['reason_id'];
            $account_deactivate->description= $request['description'];
            $account_deactivate->status = 1;
            $account_deactivate->account_status = 1;
            $account_deactivate->save();
        }
        else
        {
            AccountDeactivate::create([
                'user_id' => $user_id,
                'reason_id' => $request['reason_id'],
                'description' => $request['description'],
                'status' => 1,
                'account_status' => 1,
            ]);
        }
        User::find($user_id)?->delete();
        // Revoke the user's API token
        $user?->tokens()->delete();

        Auth::logout();


        return redirect()->route('user.login')->with(['msg'=>__('Your Account Has Been Deleted Successfully !!'),'type'=> 'danger']);



    }
}
