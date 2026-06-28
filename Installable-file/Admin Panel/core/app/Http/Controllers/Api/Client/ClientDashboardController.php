<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\SubOrder;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function dashboardInfo(){

        $client_id = Auth::guard('sanctum')->user()->id;

        $subOrders = SubOrder::where('client_id', $client_id)
            ->select('order_id', 'status')
            ->get()
            ->groupBy('order_id');

        // order status
        $pending_order_count = 0;
        $active_order_count = 0;
        $complete_order_count = 0;
        $delivered_order_count = 0;
        $cancel_order_count = 0;

        //  grouped suborders to count unique main orders by status
        foreach ($subOrders as $orderId => $orders) {
            $statuses = $orders->pluck('status')->unique();
            if ($statuses->contains(0)) {
                $pending_order_count++;
            }
            if ($statuses->contains(1)) {
                $active_order_count++;
            }
            if ($statuses->contains(2)) {
                $complete_order_count++;
            }
            if ($statuses->contains(2)) {
                $complete_order_count++;
            }
            if ($statuses->contains(3)) {
                $delivered_order_count++;
            }
            if ($statuses->contains(4)) {
                $cancel_order_count++;
            }
        }

        return response()->json([
            'pending_order' => $pending_order_count,
            'active_order' => $active_order_count,
            'completed_order' => $complete_order_count,
            'delivered_order' => $delivered_order_count,
            'cancel_order' => $cancel_order_count,
        ]);

    }
}
