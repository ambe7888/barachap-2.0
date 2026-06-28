<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;
use App\Models\UserBalance;
use App\Helpers\FlashMsg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'captcha_token' => ['nullable'],
    //         'username' => ['required', 'string', 'string', 'max:255', 'unique:users'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //         'terms_conditions' => 'required|boolean',
    //         'type' => 'required|in:0,1'
    //     ],[
    //         'captcha_token.required' => __('google captcha is required'),
    //         'first_name.required' => __('first name is required'),
    //         'last_name.required' => __('last name is required'),
    //         'first_name.max' => __('first name is must be between 191 character'),
    //         'last_name.max' => __('last name is must be between 191 character'),
    //         'username.required' => __('username is required'),
    //         'username.max' => __('username is must be between 191 character'),
    //         'username.unique' => __('username is already taken'),
    //         'email.unique' => __('email is already taken'),
    //         'email.required' => __('email is required'),
    //         'password.required' => __('password is required'),
    //         'password.confirmed' => __('both password does not matched'),
    //     ]);
    // }

    // protected function adminValidator(array $data){
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'username' => ['required', 'string', 'max:255', 'unique:admins'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }


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


    public function userRegister(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'fname' => 'required|regex:/^[\pL\pN]+$/u|max:191',
                'lname' => 'required|regex:/^[\pL\pN]+$/u|max:191',
                'email' => 'required|email|unique:users|max:191',
                'username' => 'required|unique:users|max:191',
                'password' => 'required|min:6|max:191',
                'confirm_password' => 'required|same:password',
                'terms_condition' => 'required',
            ]);

            if(!empty(get_static_option('site_google_captcha_enable'))){
                $request->validate([
                    'recaptchaResponse' => 'required',
                ]);
            }

            $email_verify_tokn = sprintf("%d", random_int(123456, 999999));
            $user = User::create([
                'first_name' => $request->fname,
                'last_name' => $request->lname,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'type' => $request->user_type ?? 0,
                'terms_condition' =>$request->terms_condition ? 1 : 0,
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
            $user_type =$user->user_type == 1 ? __('Client') : __('Provider');

          
            //send register mail
            try {
                $message = get_static_option('user_register_message') ?? __('Hello Admin a new user just have registered as a ').$user_type;
                $message = str_replace(["@name","@email","@username","@userType"],[$user->first_name.' '.$user->last_name, $user->email, $user->username,$user_type], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_register_subject') ?? __('New User Register Email'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}

            try {
                $message = get_static_option('user_register_welcome_message') ?? __('Your registration successfully completed.');
                $message = str_replace(["@name","@email","@username","@password","@userType"],[$user->first_name.' '.$user->last_name, $user->email, $user->username, $request->password, $user_type], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_register_welcome_subject') ?? __('User Register Welcome Email'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}

            //send welcome mail
            try {
                Mail::to($user->email)->send(new BasicMail([
                    'subject' =>  __('Otp Email'),
                    'message' => __('Your otp code').' '.$email_verify_tokn
                ]));
            }
            catch (\Exception $e) {}
            

            if($user)
            {
                Auth::guard('web')->login($user);
               return response()->json([
                    'status' => 'success',
                    'msg' => (__('Registration Success')),
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 'error',
                    'msg' => (__('Registration Failed')),
                ]);
            }

         
        }

        return view('auth.client_provider.register');
    }

    public function emailVerify(Request $request)
    {
        $user_details = Auth::guard('web')->user();
      

        if($request->isMethod('post')){

            $this->validate($request,[
                'email_verify_token' => 'required|max:191'
            ],[
                'email_verify_token.required' => __('verify code is required')
            ]);

            $user_details = User::where(['email_verify_token' => $request->email_verify_token,'email' => $user_details->email ])->first();

            if(!is_null($user_details)){
                $user_details->email_verified = 1;
                $user_details->save();
                 if($user_details->user_type==1){
                    return redirect()->route('user.login');
                }else{
                   return redirect()->route('user.login');
                }
            }
            toastr_warning(__('Your verification code is wrong.'));
            return back();
        }


        $verify_token = $user_details->email_verify_token ?? null;

       try {
           //check user has verify token has or not

            if(is_null($verify_token)){

                $verify_token = Str::random(8);
                $user_details->email_verify_token = Str::random(8);
                $user_details->save();

                $message_body = __('Hello').' '.$user_details->name.' <br>'.__('Here is your verification code').' <span class="verify-code">'.$verify_token.'</span>';
                Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => sprintf(__('Verify your email address %s'),get_static_option('site_title')),
                    'message' => $message_body
                ]));

            }

        }catch (\Exception $e){

        }

        return view('auth.client_provider.email_verify');
    }

    public function resendCode(){
        $user_details = Auth::guard('web')->user();
        $verify_token = $user_details->email_verify_token ?? null;

        try {

                if(is_null($verify_token)){
                    $verify_token = Str::random(8);
                    $user_details->email_verify_token = Str::random(8);
                    $user_details->save();
                }

                $message_body = __('Hello').' '.$user_details->name.' <br>'.__('Here is your verification code').' <span class="verify-code">'.$verify_token.'</span>';

                Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => sprintf(__('Verify your email address %s'),get_static_option('site_title')),
                    'message' => $message_body
                ]));



        }catch (\Exception $e){

        }

        return redirect()->back()->with(['msg' => __('Resend Email Verify Code, Please check your inbox of spam.') ,'type' => 'success' ]);
    }


}
