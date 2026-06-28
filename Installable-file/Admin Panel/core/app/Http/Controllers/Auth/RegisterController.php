<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendRegisterUserEmailJob;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Membership\app\Http\Services\MembershipService;
use Modules\Wallet\app\Models\Wallet;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function redirectTo(){
        return route('homepage');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'captcha_token' => ['nullable'],
            'username' => ['required', 'string', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'captcha_token.required' => __('google captcha is required'),
            'name.required' => __('name is required'),
            'name.max' => __('name is must be between 191 character'),
            'username.required' => __('username is required'),
            'username.max' => __('username is must be between 191 character'),
            'username.unique' => __('username is already taken'),
            'email.unique' => __('email is already taken'),
            'email.required' => __('email is required'),
            'password.required' => __('password is required'),
            'password.confirmed' => __('both password does not matched'),
        ]);
    }

    protected function adminValidator(array $data){
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    public function userNameAvailability(Request $request)
    {
        $username = User::where('username',$request->username)->first();
        if(!empty($username) && $username->username == $request->username){
            $status = 'not_available';
            $msg = __('Sorry! Username name is not available');
        }else{
            $status = 'available';
            $msg = __('Congrats! Username name is available');
        }
        return response()->json([
            'status'=>$status,
            'msg'=>$msg,
        ]);
    }

    public function emailAvailability(Request $request)
    {
        $email = User::where('email',$request->email)->first();
        if(!empty($email) && $email->email == $request->email){
            $status = 'not_available';
            $msg = __('Sorry! Email has already taken');
        }else{
            $status = 'available';
            $msg = __('Congrats! Email is available');
        }
        return response()->json([
            'status'=>$status,
            'msg'=>$msg,
        ]);
    }

    public function phoneNumberAvailability(Request $request)
    {
        $phone = User::where('phone',$request->phone)->first();
        if(!empty($phone) && $phone->phone == $request->phone){
            $status = 'not_available';
            $msg = __('Sorry! Phone Number has already taken');
        }else{
            $status = 'available';
            $msg = __('Congrats! Phone number is available');
        }

        return response()->json([
            'status'=>$status,
            'msg'=>$msg,
            'phone'=>$phone,
        ]);
    }

}
