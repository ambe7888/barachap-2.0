<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\AdminCommission;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function modulePermission(){

        return response()->success([
            'permissions'=> [
                "JobPost" => moduleExists("JobPost"),
                "LiveChat" => moduleExists("LiveChat"),
                "Subscription" => moduleExists("Subscription"),
                "Wallet" => moduleExists("Wallet"),
            ],
        ]);
    }
    public function currencyInfo(){
        return response()->json([
            'currency'=> [
                "symbol" => site_currency_symbol(),
                "code" => get_static_option('site_global_currency'),
                "position" => get_static_option('site_currency_symbol_position')
            ],
        ]);
    }

    public function adminCommissionType(){
        $admin_commission = AdminCommission::first();
        return response()->json([
            "admin_commission" => $admin_commission,
        ]);
    }

}
