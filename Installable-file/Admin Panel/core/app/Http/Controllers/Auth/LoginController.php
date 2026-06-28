<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Backend\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function adminLogin(Request $request)
    {
        $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required' => sprintf(__('required').'%s',$email_or_username),
            'password.required' => __('password required')
        ]);

        if (Auth::guard('admin')->attempt([$email_or_username => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'success',
                'status' => 'ok'
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
            'msg' => sprintf(__('Your or Password Is Wrong !!').'%s',$email_or_username),
            'type' => 'danger',
            'status' => 'not_ok',
        ]);
    }

    public function showAdminForgetPasswordForm()
    {
        return view('auth.admin.forget-password');
    }

    public function showAdminResetPasswordForm($username, $token)
    {
        return view('auth.admin.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }

    public function sendAdminForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = Admin::where('username', $request->username)->orWhere('email', $request->username)->first();

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
        $message .= __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . ' <a class="btn" href="' . route('admin.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">' . __('Click Reset Password') . '</a>';
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
        }catch(\Exeption $e){
            //handle error
            return redirect()->back()->with([
                'msg' => $e->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    public function AdminResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = Admin::where('username', $request->username)->first();
        $user = Admin::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('admin.login')->with(['msg' =>__('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

}
