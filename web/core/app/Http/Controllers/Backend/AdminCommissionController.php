<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Backend\AdminCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminCommissionController extends Controller
{
    public function admin_commission_all(){
        $commission =AdminCommission::first();
        return view('backend.pages.commission.admin-commission-all',compact('commission'));
    }

    public function admin_commission_update(Request $request,$id=null){
        if(!empty($id)){
            AdminCommission::where('id',$id)->update([
                'system_type'=> 'commission',
                'commission_charge_type'=>$request->commission_charge_type ?? 'percentage',
                'commission_charge'=>$request->commission_charge ?? 10,
            ]);
        }else{
            AdminCommission::create([
                'system_type' => 'commission',
                'commission_charge_type' => $request->commission_charge_type ?? 'percentage',
                'commission_charge' => $request->commission_charge ?? 10,
            ]);
        }

        Cache::forget('admin_commission_data');

        return redirect()->back()->with(FlashMsg::item_new(__('Update Success')));
    }
}
