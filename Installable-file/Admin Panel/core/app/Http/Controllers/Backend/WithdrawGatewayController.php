<?php

namespace App\Http\Controllers\Backend;

use App\Actions\Services\ImageModifier;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGatewayRequest;
use App\Http\Services\OrderServiceNotification;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\WithdrawGateway;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WithdrawGatewayController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function gateway_settings()
    {
        $gateways  = WithdrawGateway::latest()->get();
        return view('backend.pages.withdraw.gateways',compact('gateways'));
    }

    public function gateway_create(StoreGatewayRequest $request){
        WithdrawGateway::create($request->validated());
        return back()->with(FlashMsg::item_new(__('Payment Gateway Created Successfully.')));
    }

    public function gateway_update(StoreGatewayRequest $request){
        $data = $request->validated();

        $id = $data["id"];
        unset($data["id"]);

        WithdrawGateway::where("id", $id)->update($data);

        return back()->with(FlashMsg::item_new(__('Payment Gateway Updated Successfully.')));
    }

    public function delete_gateway($id){
        WithdrawGateway::where('id', $id)->delete();
        return back()->with(FlashMsg::item_delete(__('Payment Gateway Deleted Successfully.')));
    }

    public function change_status($id){
        $gateway = WithdrawGateway::findOrFail($id);
        $gateway->status == 1 ? $status = 2 : $status = 1;
        WithdrawGateway::where('id', $id)->update(['status' => $status]);
        return back()->with(FlashMsg::item_delete(__('Status Successfully Changed.')));
    }

    //withdraw amount settings
    public function withdraw_settings(Request $request)
    {
        $request->validate([
            'minimum_withdraw_amount'=>'numeric|gt:0',
            'maximum_withdraw_amount'=>'numeric|gt:0',
        ],
            [
                'minimum_withdraw_amount.numeric'=>'Please enter only numeric value.',
                'maximum_withdraw_amount.numeric'=>'Please enter only numeric value.'
            ]);
        if($request->isMethod('post')){
            $fields = ['minimum_withdraw_amount','maximum_withdraw_amount'];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('backend.pages.withdraw.withdraw-settings');
    }

    public function withdraw_request()
    {
        $all_request  = WithdrawRequest::whereHas('user')->latest()->paginate(10);
        return view('backend.pages.withdraw.requests',compact('all_request'));
    }

    public function withdraw_request_details($id)
    {
        $withdraw_request = WithdrawRequest::whereHas('user')
            ->where('id',$id)
            ->first();

        if (!$withdraw_request) {
            abort(404);
        }

        try {
            AdminNotification::where('identity', $id)->update(['is_read'=> 'read']);
        }catch (\Exception $exception){}

        return view('backend.pages.withdraw.request-details',compact('withdraw_request'));
    }

    public function withdraw_request_update(Request $request)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $withdraw_request = WithdrawRequest::where('id',$request->request_id)->first();
        $deleteOldImage =  'assets/uploads/withdraw-request/'.$withdraw_request->image;

        if($image = $request->file('image')){
            if(file_exists($deleteOldImage)){
                File::delete($deleteOldImage);
            }
            $image_original_name = $request->image->getClientOriginalName();
            $image_name = $image_original_name.'-'.time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('assets/uploads/withdraw-request', $image_name);
        }else{
            $image_name = $withdraw_request->image;
        }

        WithdrawRequest::where('id',$request->request_id)->update([
            'status' => $request->status,
            'note' => $request->note,
            'image' => $image_name
        ]);

        // update provider balance
        if($request->status == 2){
            $provider_id =  $withdraw_request->user_id;
            $providerBalance = UserBalance::where('user_id', $provider_id)->first();
            $total_withdrawal_amount =$withdraw_request->amount;

            // Check if provider balance exists
            if (!$providerBalance) {
                return response()->json([
                    'msg' => __("Provider balance not found.")
                ])->setStatusCode(404);
            }

            // Calculate the withdrawal fee
            $withdraw_fee = 0;
            if (get_static_option('withdraw_fee_type') == 'percentage') {
                $withdraw_fee = ($total_withdrawal_amount * get_static_option('withdraw_fee')) / 100;
            } else {
                $withdraw_fee = get_static_option('withdraw_fee');
            }

            // Calculate the total amount to deduct from the provider's balance
            $total_deduction_amount = $total_withdrawal_amount + $withdraw_fee;

            // Validate that the withdrawal amount + fee does not exceed available balance
            if ($total_deduction_amount > $providerBalance->available_balance) {
                return response()->json([
                    'msg' => __("Insufficient balance for withdrawal including fees.")
                ])->setStatusCode(422);
            }

            // Update the provider's balance
            $providerBalance->update([
                'available_balance' => $providerBalance->available_balance - $total_deduction_amount,
                'total_withdrawn' => $providerBalance->total_withdrawn + $total_withdrawal_amount,
            ]);

        }else{
            $provider_id =  $withdraw_request->user_id;
            $providerBalance = UserBalance::where('user_id', $provider_id)->first();
            $total_withdrawal_amount =$withdraw_request->amount;

            // Check if provider balance exists
            if (!$providerBalance) {
                return response()->json([
                    'msg' => __("Provider balance not found.")
                ])->setStatusCode(404);
            }

            // Calculate the withdrawal fee
            $withdraw_fee = 0;
            if (get_static_option('withdraw_fee_type') == 'percentage') {
                $withdraw_fee = ($total_withdrawal_amount * get_static_option('withdraw_fee')) / 100;
            } else {
                $withdraw_fee = get_static_option('withdraw_fee');
            }
        }

        if($request->status == 1){
            $status_text = __('pending');
        }
        if($request->status == 2){
            $status_text = __('complete');
        }
        if($request->status == 3){
            $status_text = __('cancel');
        }
        if($request->status == 4){
            $status_text = __('processing');
        }

        // provider notification send
        $message = __('Your withdraw request has been') .' '. $status_text;
        user_notification($request->request_id, $withdraw_request->user_id,'withdraw', $message, 'unread');

        try {
            $provider_info = User::find($withdraw_request->user_id);
            $provider_notification_data = [
                "title" => $message,
                "detailed_title" => "-",
                "identify" => $request->request_id,
                "user_id" => $provider_info->id ?? 0,
                "body" => $message,
                "description" => "-",
                "type" => "withdraw",
                "sound" => "default",
                "screen" => "-",
            ];

            // Pass the token as an array
            $this->orderServiceNotification->sendFirebaseNotification([$provider_info->firebase_token], $message, $message, $provider_notification_data);

        }catch (\Exception $exception){}


        $provider = User::find($withdraw_request->user_id);
            // Define the message with placeholders
            $messageTemplate = __('Your withdrawal request has been @status. Requested Amount: @amount. Withdrawal Fee: @fee.');

            // Replace placeholders with actual values
            $message = str_replace(
                ['@status', '@amount', '@fee'],
                [$status_text, $withdraw_request->amount, $withdraw_fee],
                $messageTemplate
            );

            try {
                Mail::to($provider->email)->send(new BasicMail([
                    'subject' =>  __('Withdrawal Request Status Update'),
                    'message' => $message
                ]));
            }catch (\Exception $exception){}



        return back()->with(FlashMsg::item_new(__('Status Successfully Updated.')));
    }


    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_request = WithdrawRequest::latest()->paginate(10);
            return view('backend.pages.withdraw.search-result', compact('all_request'))->render();
        }
    }

    public function withdraw_fee_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'withdraw_fee' => 'required|numeric|min:0',
                'withdraw_fee_type' => 'required',
            ]);
            $all_fields = ['withdraw_fee','withdraw_fee_type'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }

            return back()->with(FlashMsg::item_new(__('Withdraw Fee Settings Updated Successfully.')));
        }

        return view('backend.pages.withdraw.withdraw-fee-settings');
    }
}
