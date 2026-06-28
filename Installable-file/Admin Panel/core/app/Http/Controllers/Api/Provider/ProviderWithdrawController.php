<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderHandleWithdrawRequest;
use App\Http\Resources\Withdrawal\ProviderWithdrawalHistoryResource;
use App\Models\UserBalance;
use App\Models\WithdrawGateway;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class ProviderWithdrawController extends Controller
{

    public function withdraw_settings()
    {
        $user_id = auth('sanctum')->user()->id;
        $minimum_withdraw_amount = get_static_option('minimum_withdraw_amount') ?? 0;
        $maximum_withdraw_amount = get_static_option('maximum_withdraw_amount') ?? 0;
        $withdraw_fee_type = get_static_option('withdraw_fee_type') ?? '';
        $withdraw_fee = get_static_option('withdraw_fee') ?? 0;

        $withdraw_gateways = WithdrawGateway::select('id', 'name', 'field')
            ->where('status', 1)
            ->get()
            ->transform(function ($item) {
                $unserializedField = unserialize($item->field);
                $item->field = is_array($unserializedField) ? $unserializedField : [];
                return $item;
            });


        return response()->json([
            'withdraw_gateways' => $withdraw_gateways,
            'minimum_withdraw_amount' => $minimum_withdraw_amount,
            'maximum_withdraw_amount' => $maximum_withdraw_amount,
            'withdraw_fee_type' => $withdraw_fee_type,
            'withdraw_fee' => $withdraw_fee,
        ]);

    }

    //withdraw request
    public function withdraw_request(ProviderHandleWithdrawRequest $request)
    {
        $data = $request->validated();

        if(strlen($request->gateway_fields) <=10){
            return response()->json([
                'msg'=> __("Gateway field is required")
            ])->setStatusCode(422);
        }

        $withdraw_fee = 0;
        if(get_static_option('withdraw_fee_type') == 'percentage'){
            $withdraw_fee = ($data["amount"]*get_static_option('withdraw_fee'))/100;
        }else{
            $withdraw_fee = get_static_option('withdraw_fee');
        }


        $amount = $data["amount"] + $withdraw_fee;

        if($amount < get_static_option('minimum_withdraw_amount') || $amount > get_static_option('maximum_withdraw_amount')){
            return response()->json([
                'msg'=> __("Please enter a valid amount between ".float_amount_with_currency_symbol(get_static_option('minimum_withdraw_amount')). '-' .float_amount_with_currency_symbol(get_static_option('maximum_withdraw_amount')))
            ])->setStatusCode(422);
        }


        // calculate current provider blance
        $user_id = auth('sanctum')->user()->id;
        $provider_balance = UserBalance::where('user_id', $user_id)->first();

        // If the provider has no balance record, assume balance is zero
        if (!$provider_balance || $provider_balance->available_balance <= 0) {
            return response()->json([
                'msg' => __("You have no balance available for withdrawal.")
            ])->setStatusCode(422);
        }

        // Validate that the withdrawal amount is not more than the current balance
        if ($provider_balance && $amount > $provider_balance->available_balance) {
            return response()->json([
                'msg' => __("Insufficient balance for withdrawal. Available balance: :balance", [
                    'balance' => float_amount_with_currency_symbol($provider_balance->available_balance)
                ])
            ])->setStatusCode(422);
        }

        // Add the fee to the data array and Store the calculated fee
        $data["fee"] = $withdraw_fee;

        if($data["amount"]){
            $withdraw = WithdrawRequest::create($data);

             admin_notification($withdraw->id, $withdraw->user_id, 'withdraw', 'New withdraw request', 'unread');

            return response()->json([
                'msg' => __("Successfully sent your request"),
            ]);
        }
        return response()->json([
            'msg' => __('Your requested amount is greater than your wallet balance')
        ])->setStatusCode(422);
    }

    //withdraw history
    public function withdraw_history()
    {
        $query = WithdrawRequest::where('user_id', auth('sanctum')->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Transform the results
        $all_request = $query->through(function ($item) {
            $item->gateway_fields = json_decode($item->gateway_fields, true);
            return $item;
        });

        if($all_request){
            return response()->json([
                'histories' => ProviderWithdrawalHistoryResource::collection($all_request),
                'pagination' => [
                    'total' => $all_request->total(),
                    'count' => $all_request->count(),
                    'per_page' => $all_request->perPage(),
                    'current_page' => $all_request->currentPage(),
                    'last_page' => $all_request->lastPage(),
                    'next_page_url' => $all_request->nextPageUrl(),
                    'prev_page_url' => $all_request->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'msg' => __('No history found.'),
        ], 404);
    }


    public function current_balance_info()
    {

        $userId = auth('sanctum')->user()->id;
        $providerBalance = UserBalance::where('user_id', $userId)->first();

        if($providerBalance){
            return response()->json([
                'available_balance' => $providerBalance ? $providerBalance->available_balance : 0,
                'total_earnings' => $providerBalance ? $providerBalance->total_earnings : 0,
                'total_withdrawn' => $providerBalance ? $providerBalance->total_withdrawn : 0,
            ]);
        }

        return response()->json([
            'msg' => __('balance not found.')
        ], 404);
    }
}
