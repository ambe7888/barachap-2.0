<?php

namespace Modules\Tax\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTaxManageController extends Controller
{
    public function settings()
    {
        return view("tax::backend.settings");
    }

    public function handleSettings(Request $request){

        if ($request->tax_inclusive_exclusive == 'exclusive'){
            $request->validate([
                "tax_rate_by_country" => "required"
            ]);
        }

        update_static_option("tax_inclusive_exclusive", $request->tax_inclusive_exclusive ?? "");
        update_static_option("tax_rate_by_country", $request->tax_rate_by_country ?? 0);
        update_static_option("tax_system", 'zone_wise_tax_system' ?? "");

        return back()->with([
            "msg" => __("Tax settings updated successfully."),
            "type" => "success"
        ]);
    }

}
