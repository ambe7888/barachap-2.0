<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderCancelPolicyResource;
use App\Models\OrderCancellationPolicy;
use Illuminate\Http\Request;

class OrderCancelPolicyController extends Controller
{
    public function cancelPolicyDetails()
    {
        $cancelPolicy = OrderCancellationPolicy::first();
        if ($cancelPolicy)
        {
            return response()->json([
                'cancel_policy' => new OrderCancelPolicyResource($cancelPolicy),
            ]);
        }
        return response()->json([
            'cancel_policy' => [],
        ]);

        

    }
}
