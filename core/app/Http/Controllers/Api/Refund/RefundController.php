<?php

namespace App\Http\Controllers\Api\Refund;

use App\Http\Controllers\Controller;
use App\Models\RefundGateway;
use Illuminate\Http\Request;

class RefundController extends Controller
{

    public function refund_gateway_list()
    {
        $refund_gateways = RefundGateway::select('id', 'name', 'field')
            ->where('status', 1)
            ->get()
            ->transform(function ($item) {
                $unserializedField = unserialize($item->field);
                $item->field = is_array($unserializedField) ? $unserializedField : [];
                return $item;
            });


        return response()->json([
            'refund_gateways' => $refund_gateways,
        ]);

    }

    

    


  
}
