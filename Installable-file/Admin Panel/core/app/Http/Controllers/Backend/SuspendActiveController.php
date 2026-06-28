<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SuspendActiveController extends Controller
{
    //suspend user
    public function suspend(Request $request, $id)
    {
        if($request->isMethod('post')){
            $user = User::where('id',$id)->first();
            User::where('id',$id)->update(['is_suspend'=>1]);
            $suspend_message_account = __('Account Suspend.');

            user_notification($id,$id, 'suspend', $suspend_message_account, 'unread');

        //Email to user according to their id
        try {
            $message = get_static_option('account_suspend_message') ?? __('Account Suspend Message');
            $message = str_replace(["@name"],[$user->fullname], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('account_suspend_subject') ?? __('Account Suspend Email'),
                'message' => $message
            ]));
        }catch (\Exception $e) {}

            return back()->with(FlashMsg::item_new(__('Account Successfully Suspended.')));
        }
        return view('backend.pages.account.suspend');
    }

    //unsuspend user
    public function unsuspend($id)
    {
        $user = User::find($id);
        User::where('id',$id)->update(['is_suspend'=>0]);
         user_notification($id,$user->id,'Account',__('Account Unsuspended'));

        //Email to user according to their id
        try {
            $message = get_static_option('account_unsuspend_message') ?? __('Account Unsuspend Message');
            $message = str_replace(["@name"],[$user->fullname], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('account_unsuspend_subject') ?? __('Account Unsuspend Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return back()->with(FlashMsg::item_new(__('Account Successfully Unsuspended.')));
    }
}
