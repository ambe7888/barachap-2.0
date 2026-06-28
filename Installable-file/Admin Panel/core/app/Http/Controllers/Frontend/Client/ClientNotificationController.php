<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    public function all_notification()
    {
       
        $client_id=Auth::user()->id;
        $all_notifications = UserNotification::where("user_id",$client_id)->latest()->orderBy('is_read','ASC')->paginate(10);
        return view('frontend.frontend.client.pages.notification.all-notification',compact('all_notifications'));
    }

    // search history
    public function search_notification(Request $request)
    {
        $client_id=Auth::user()->id;
        $all_notifications = UserNotification::where("user_id",$client_id)
            ->where(function ($query) use($request) {
            $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('is_read', 'LIKE', strip_tags($request->string_search));
            })
            ->latest()
            ->orderBy('is_read','ASC')
            ->paginate(10);

        return $all_notifications->total() >= 1 ? view('frontend.frontend.client.pages.notification.search-result', compact('all_notifications'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {

        if($request->ajax()){
            $client_id=Auth::user()->id;
            if(empty($request->string_search)){
                $all_notifications = UserNotification::where("user_id",$client_id)->latest()->orderBy('is_read','ASC')->paginate(10);
                return view('frontend.frontend.client.pages.notification.search-result', compact('all_notifications'))->render();
            }else{
                $all_notifications = UserNotification::where("user_id",$client_id)->where(function ($query) use($request) {
                    $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%")
                        ->orWhere('is_read', 'LIKE', strip_tags($request->string_search));
                })
                    ->latest()
                    ->orderBy('is_read','ASC')
                    ->paginate(10);

                return $all_notifications->total() >= 1 ? view('frontend.frontend.client.pages.notification.search-result', compact('all_notifications'))->render() : response()->json(['status'=>__('nothing')]);

            }
        }
    }

    // read notification
    public function read_notification()
    {
        UserNotification::where('is_read','unread')
            ->update(['is_read' => 'read']);
        return response()->json(['status' => 'success']);
    }
}
