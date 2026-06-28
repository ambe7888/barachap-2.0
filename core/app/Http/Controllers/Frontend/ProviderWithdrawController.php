<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderHandleWithdrawRequest;
use App\Http\Resources\Withdrawal\ProviderWithdrawalHistoryResource;
use App\Models\UserBalance;
use App\Models\WithdrawGateway;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;
use App\Helpers\FlashMsg;

class ProviderWithdrawController extends Controller
{

    // public function withdraw_settings()
    // {
    //     $user_id = auth('sanctum')->user()->id;
    //     $minimum_withdraw_amount = get_static_option('minimum_withdraw_amount') ?? 0;
    //     $maximum_withdraw_amount = get_static_option('maximum_withdraw_amount') ?? 0;
    //     $withdraw_fee_type = get_static_option('withdraw_fee_type') ?? '';
    //     $withdraw_fee = get_static_option('withdraw_fee') ?? 0;

    //     $withdraw_gateways = WithdrawGateway::select('id', 'name', 'field')
    //         ->where('status', 1)
    //         ->get()
    //         ->transform(function ($item) {
    //             $unserializedField = unserialize($item->field);
    //             $item->field = is_array($unserializedField) ? $unserializedField : [];
    //             return $item;
    //         });


    //     return response()->json([
    //         'withdraw_gateways' => $withdraw_gateways,
    //         'minimum_withdraw_amount' => $minimum_withdraw_amount,
    //         'maximum_withdraw_amount' => $maximum_withdraw_amount,
    //         'withdraw_fee_type' => $withdraw_fee_type,
    //         'withdraw_fee' => $withdraw_fee,
    //     ]);

    // }

    //withdraw request
    public function withdraw_request(Request $request)
    {
        
         if($request->isMethod('post'))
         {
            $request->validate([
                'amount'=>'required',
                'withdraw_method'=>'required',
                'method_fields' =>'required|array'
                
            ],
            [
                'amount.required' => 'Please Enter Amount',
                'withdraw_method.required' => 'Please Select A Withdraw Method',
                'method_fields.required' => 'Please fill up the method fields'
                
            ]);

            

            $amount=$request->amount;
            $withdraw_gateway_id=$request->withdraw_method;
            $methodFields = $request->input('method_fields');
            $methodFields = json_encode($methodFields);
        
            $withdraw_fee = 0;
            if(get_static_option('withdraw_fee_type') == 'percentage'){
                $withdraw_fee = ($amount*get_static_option('withdraw_fee'))/100;
            }else{
                $withdraw_fee = get_static_option('withdraw_fee');
            }
    
    
            $amount = $amount + $withdraw_fee;
    
            if($amount < get_static_option('minimum_withdraw_amount') || $amount > get_static_option('maximum_withdraw_amount')){
                $msg= __("Please enter a valid amount between ".float_amount_with_currency_symbol(get_static_option('minimum_withdraw_amount')). '-' .float_amount_with_currency_symbol(get_static_option('maximum_withdraw_amount')));
                return back()->with(FlashMsg::item_delete($msg));
            }
    
    
            // calculate current provider blance
            $user_id = auth('sanctum')->user()->id;
            $provider_balance = UserBalance::where('user_id', $user_id)->first();
    
            // If the provider has no balance record, assume balance is zero
            if (!$provider_balance || $provider_balance->available_balance <= 0) {
                return back()->with(FlashMsg::item_delete(__("You have no balance available for withdrawal.")));
            }
    
            // Validate that the withdrawal amount is not more than the current balance
            if ($provider_balance && $amount > $provider_balance->available_balance) {
                $msg=__("Insufficient balance for withdrawal. Available balance: ").float_amount_with_currency_symbol($provider_balance->available_balance);
                return back()->with(FlashMsg::item_delete($msg));
            }
    
            if($amount){
                $withdraw = WithdrawRequest::create([
                    'amount'=>$amount,
                    'gateway_id'=>$withdraw_gateway_id,
                    'gateway_fields' => $methodFields,
                    'user_id' => $user_id,
                    'fee' => $withdraw_fee
                ]);
    
                 admin_notification($withdraw->id, $withdraw->user_id, 'withdraw', 'New withdraw request', 'unread');
                 return back()->with(FlashMsg::item_new(__("Successfully sent your request")));
               
            }
            return back()->with(FlashMsg::item_delete( __('Your requested amount is greater than your wallet balance')));
           
         }

         $withdraw_methods=WithdrawGateway::all();
         return view("frontend.frontend.provider.pages.withdraw.withdraw-request-send",compact('withdraw_methods'));
        
    }

    //withdraw history
    public function withdraw_history()
    {
        $all_request = WithdrawRequest::where('user_id', Auth::user()->id)
            ->latest()
            ->paginate(10);
        return view('frontend.frontend.provider.pages.withdraw.requests',compact('all_request'));
    }


    // public function current_balance_info()
    // {

    //     $userId = auth('sanctum')->user()->id;
    //     $providerBalance = UserBalance::where('user_id', $userId)->first();

    //     if($providerBalance){
    //         return response()->json([
    //             'available_balance' => $providerBalance ? $providerBalance->available_balance : 0,
    //             'total_earnings' => $providerBalance ? $providerBalance->total_earnings : 0,
    //             'total_withdrawn' => $providerBalance ? $providerBalance->total_withdrawn : 0,
    //         ]);
    //     }

    //     return response()->json([
    //         'msg' => __('balance not found.')
    //     ], 404);
    // }


    public function withdraw_request_details($id,$notificationId=null)
    {
        
        $withdraw_request = WithdrawRequest::whereHas('user')
            ->where('id',$id)
            ->first();

        if (!$withdraw_request) {
            abort(404);
        }

        try {
            UserNotification::where('identity', $notificationId)->update(['is_read'=> 'read']);
        }catch (\Exception $exception){}

        return view('frontend.frontend.provider.pages.withdraw.request-details',compact('withdraw_request'));
    }
}
