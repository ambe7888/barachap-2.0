<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PaymentGatewayResourceResource;
use Illuminate\Http\Request;
use Modules\PaymentGateways\app\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
        public function gatewayList(Request $request)
        {

            $payment_gateways = PaymentGateway::where('status' , 1)->get()->toArray();
            $cash_on_delivery_option = get_static_option('cash_on_delivery');
            $cash_on_delivery = [];
            if (!empty($cash_on_delivery_option))
            {
                $index = !empty($payment_gateways) ? count($payment_gateways) + 1 : 0;
                $id = !empty($payment_gateways) ? data_get(max($payment_gateways), 'id') + 1 : 0;

                $cash_on_delivery[$index] = [
                    'id' => $id,
                    'name' => 'cash_on_delivery',
                    'description' => '',
                    'image' => '',
                    'status' => 1,
                    'test_mode' => 1,
                    'credentials' => ''
                ];
            }

            $merged = collect(array_merge($payment_gateways, $cash_on_delivery));

            return PaymentGatewayResourceResource::collection($merged);
        }
}
