<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\SubOrder;

class UpdateMainOrderStatus
{
    public function execute(Order $order, SubOrder $subOrder){
        // Get all sub-order statuses for the main order
        $order_wise_all_suborder_status = SubOrder::where('order_id', $subOrder->order_id)
            ->pluck('status')
            ->toArray();

        // Step 1: If any sub-order status is 0 or 1, set main order status to 0
        if (in_array(0, $order_wise_all_suborder_status) || in_array(1, $order_wise_all_suborder_status)) {
            $order->status = 0;
        } else {
            // Step 2: If no sub-order has status 0 or 1, set main order status to 1
            $order->status = 1;
        }

        $order->save();
    }

}
