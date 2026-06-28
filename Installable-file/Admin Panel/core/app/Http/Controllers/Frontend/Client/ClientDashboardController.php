<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubOrder;
use App\Models\Service;
use App\Models\UserBalance;
use App\Models\Staff;
use Modules\JobPost\app\Models\JobPost;

class ClientDashboardController extends Controller
{
    public function userDashboard()
    {

        $user=Auth::user();

        if($user->type==1)
        {
            $user_id=$user->id;
            $pending_order = SubOrder::where('client_id', $user_id)
            ->where('status', 0)
            ->distinct('order_id')
            ->count('order_id');

            $active_order = SubOrder::where('client_id', $user_id)
            ->where('status', 1)
            ->distinct('order_id')
            ->count('order_id');

            $completed_order=SubOrder::where('client_id', $user_id)
            ->where('status', 2)
            ->distinct('order_id')
            ->count('order_id');


            $total_order=SubOrder::where('client_id', $user_id)
            ->distinct('order_id')
            ->count('order_id');
            $total_job=JobPost::where("client_id",$user_id)->count();

            return view('frontend.frontend.client.pages.dashboard',compact('pending_order','active_order','completed_order','total_order','total_job'));
        }

    }



}
