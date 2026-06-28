<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\CalculateTotalMonthIncome;
use App\Http\Services\Api\CalculateTotalWeekIncome;
use App\Http\Services\Api\CalculateTotalYearIncome;
use App\Models\Review;
use App\Models\SubOrder;
use App\Models\UserBalance;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderDashboardController extends Controller
{
    public function dashboardInfo(){

        // check provider
        if (Auth::guard('sanctum')->user()->type == 1) {
            return response()->json([
                'message' => __('Only access provider')
            ], 403);
        }


        $provider_id = Auth::guard('sanctum')->user()->id;

        // order data count
        $pending_order = SubOrder::where(['status'=> 0, 'provider_id'=> $provider_id])->count();
        $active_order = SubOrder::where(['status'=> 1, 'provider_id'=> $provider_id])->count();
        $complete_order = SubOrder::where(['status'=> 2, 'provider_id'=> $provider_id])->count();
        $delivered_order = SubOrder::where(['status'=> 3, 'provider_id'=> $provider_id])->count();
        $cancel_order = SubOrder::where(['status'=> 4, 'provider_id'=> $provider_id])->count();
        $decline_order = SubOrder::where(['status'=> 5, 'provider_id'=> $provider_id])->count();
        // Order completion rate
        $total_orders = $pending_order + $active_order + $complete_order + $cancel_order;
        $order_completion_rate = $total_orders > 0 ? ($complete_order / $total_orders) * 100 : 0;

        // Customer satisfaction rate
        $total_ratings = Review::where('user_id', $provider_id)->count();
        $average_rating = Review::where('user_id', $provider_id)->avg('rating');
        $customer_satisfaction_rate = $total_ratings > 0 ? ($average_rating / 5) * 100 : 0;

        $provider_balance = UserBalance::where('user_id', $provider_id)->first();


        return response()->json([
            'pending_order' => $pending_order,
            'active_order' => $active_order,
            'completed_order' => $complete_order,
            'delivered_order' => $delivered_order,
            'cancel_order' => $cancel_order,
            'decline_order' => $decline_order,
            'order_completion_rate' => round($order_completion_rate, 2), // In percentage
            'customer_satisfaction_rate' => round($customer_satisfaction_rate, 2), // In percentage
            'provider_balance' => $provider_balance,
        ]);

    }


    public function getTotalIncomeData(Request $request) {
        $interval = $request->input('interval');

        switch ($interval) {
            case 'this_week': // recent week
                $total_earnings = $this->calculateEarnings('recent_week');
                break;

            case 'last_week': // last week
                $total_earnings = $this->calculateEarnings('last_week');
                break;

            case 'this_month': // recent month
                $total_earnings = $this->calculateEarnings('recent_month');
                break;

            case 'last_month': // last month
                $total_earnings = $this->calculateEarnings('last_month');
                break;

            case 'this_year': // recent year
                $total_earnings = $this->calculateEarnings('recent_year');

                break;
            case 'last_year': // last year
                $total_earnings = $this->calculateEarnings('last_year');
                break;

            default:
                $total_earnings = 0;
                break;
        }

        return response()->json(['total_earnings' => $total_earnings]);
    }



    private function calculateEarnings($interval) {
        switch ($interval) {

            case 'recent_week':
                $startWeek = Carbon::now()->startOfWeek();
                $endWeek = Carbon::now()->endOfWeek();
                $result=CalculateTotalWeekIncome::calculateWeekIncome($startWeek,$endWeek);
                return $result;
                break;

            case 'last_week':
                $startWeek = Carbon::now()->startOfWeek();
                $startLastWeek=$startWeek->subweek();
                $endWeek = Carbon::now()->endOfWeek();
                $endLastWeek=$endWeek->subweek();
                $result=CalculateTotalWeekIncome::calculateWeekIncome($startLastWeek,$endLastWeek);
                return $result;
                break;

            case 'recent_month':
                $startMonth = Carbon::now()->startOfMonth();
                $endMonth = Carbon::now()->endOfMonth();
                $result=CalculateTotalMonthIncome::calculateMonthIncome($startMonth,$endMonth);
                return $result;
                break;

            case 'last_month':
                $startMonth = Carbon::now()->subMonth()->startOfMonth();
                $endMonth = Carbon::now()->subMonth()->endOfMonth();
                $result=CalculateTotalMonthIncome::calculateMonthIncome($startMonth,$endMonth);
                return $result;
                break;

            case 'recent_year':
                $recentYear = Carbon::now()->year;
                $result=CalculateTotalYearIncome::calculateYearIncome($recentYear);
                return $result;
                break;

            case 'last_year':
                $lastYear = Carbon::now()->subYear()->year;
                $result=CalculateTotalYearIncome::calculateYearIncome($lastYear);
                return $result;
                break;
            default:
                return 0;
        }
    }


}
