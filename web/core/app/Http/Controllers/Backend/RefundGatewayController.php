<?php

namespace App\Http\Controllers\Backend;

use App\Actions\Services\ImageModifier;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGatewayRequest;
use App\Http\Services\OrderServiceNotification;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\RefundGateway;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\WithdrawGateway;
use App\Models\WithdrawRequest;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RefundGatewayController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function gateway_settings()
    {
        $gateways  = RefundGateway::latest()->get();
        return view('backend.pages.refund.gateways',compact('gateways'));
    }

    public function gateway_create(StoreGatewayRequest $request){
        RefundGateway::create($request->validated());
        return back()->with(FlashMsg::item_new(__('Payment Gateway Created Successfully.')));
    }

    public function gateway_update(StoreGatewayRequest $request){
        $data = $request->validated();

        $id = $data["id"];
        unset($data["id"]);

        RefundGateway::where("id", $id)->update($data);

        return back()->with(FlashMsg::item_new(__('Payment Gateway Updated Successfully.')));
    }

    public function delete_gateway($id){
        RefundGateway::where('id', $id)->delete();
        return back()->with(FlashMsg::item_delete(__('Payment Gateway Deleted Successfully.')));
    }

    public function change_status($id){
        $gateway = RefundGateway::findOrFail($id);
        $gateway->status == 1 ? $status = 2 : $status = 1;
        RefundGateway::where('id', $id)->update(['status' => $status]);
        return back()->with(FlashMsg::item_delete(__('Status Successfully Changed.')));
    }

    //withdraw amount settings
   

    


}
