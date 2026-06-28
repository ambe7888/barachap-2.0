<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;

class AdminProviderController extends Controller
{
    public function providerDetails($id)
    {
        $user = User::with('balance', 'services', 'jobs', 'subOrders')->where('id', $id)->first();
        if (empty($user)){
            return redirect_404_page();
        }

        return view('backend.pages.provider.profile',compact('user'));

    }
}
