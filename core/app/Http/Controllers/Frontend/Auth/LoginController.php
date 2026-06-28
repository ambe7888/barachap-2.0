<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Backend\Admin;
use App\Models\User;
use App\Models\SubOrder;
use App\Models\Service;
use App\Models\UserBalance;
use App\Models\Staff;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;




class LoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        return route('homepage');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function username()
    {
        return 'username';
    }

    public function showLoginForm()
    {
        return view('auth.client_provider.login');
    }


    public function userLogin(Request $request)
    {
        $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required' => sprintf(__('required ').'%s',$email_or_username),
            'password.required' => __('password required')
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msg' => $validator->errors()->first(),
                'type' => 'error',
                'status' => 'not_ok'
            ], 422);
        }



        if (Auth::attempt([$email_or_username => $request->username, 'password' => $request->password], $request->get('remember'))) {
            $user= Auth::user();
            $user_type = $user->type;
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'success',
                'status' => 'ok',
                'user_type' => $user_type,
            ]);

        }else{
            // Authentication failed
            return response()->json([
                'msg' => __('Invalid credentials'),
                'type' => 'error',
                'status' => 'failed'
            ], 401);
        }

        return response()->json([
            'msg' => sprintf(__('Your UserName or Password Is Wrong !!').'%s',$email_or_username),
            'type' => 'danger',
            'status' => 'not_ok',
        ]);
    }

    public function showUserForgetPasswordForm()
    {
        return view('auth.client_provider.forget-password');
    }

    public function showUserResetPasswordForm($username, $token)
    {
        return view('auth.client_provider.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }

    public function sendUserForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if(is_null($user_info)){
            return redirect()->back()->with([
                'msg' => __('your username or email does not found in our server'),
                'type' => 'danger'
            ]);
        }

        $token_id = Str::random(30);
        $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
        DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);


        $message = __('Hello').' '.$user_info->username."\n";
        $message .= __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . ' <a class="btn" href="' . route('user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">' . __('Click Reset Password') . '</a>';
        $subject = __('Your Mail For Reset Password Link');
        try{
            Mail::to($user_info->email)->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }catch(\Exception $e){
            //handle error
            return redirect()->back()->with([
                'msg' => $e->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    public function UserResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = User::where('username', $request->username)->first();
        $user = User::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('user.login')->with(['msg' =>__('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with(['msg'=>__("You Logged Out !!"),'type'=> 'danger']);
    }

}
