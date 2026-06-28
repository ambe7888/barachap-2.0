<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\provider\CalculateTotalHourIncome;
use App\Http\Services\provider\CalculateTotalMonthIncome;
use App\Http\Services\provider\CalculateTotalWeekIncome;
use App\Http\Services\provider\CalculateTotalYearIncome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SubOrder;
use App\Models\Staff;
use App\Models\Service;
use App\Models\UserBalance;
use Illuminate\Support\Facades\Auth;

class ProviderDashboardController extends Controller
{
    public function userDashboard()
    {
        $user=Auth::user();

        if($user->type==0)
        {
            $user_id=$user->id;
            $pending_order = SubOrder::where('provider_id', $user_id)
            ->where('status', 0)
            ->distinct('order_id')
            ->count('order_id');

            $active_order = SubOrder::where('provider_id', $user_id)
            ->where('status', 1)
            ->distinct('order_id')
            ->count('order_id');

            $completed_order=SubOrder::where('provider_id', $user_id)
            ->where('status', 2)
            ->distinct('order_id')
            ->count('order_id');
            $total_order=SubOrder::where('provider_id', $user_id)
            ->distinct('order_id')
            ->count('order_id');
            $total_service=Service::where('provider_id',$user_id)->count();
            $total_income=UserBalance::where('user_id',$user_id)->sum('available_balance');
            $total_staff=Staff::where('provider_id',$user_id)->count();
            return view('frontend.frontend.provider.pages.dashboard',compact('pending_order','active_order','completed_order','total_order','total_service','total_income','total_staff'));
        }

    }

    public function getTotalIncomeData(Request $request) {
        $interval = $request->input('interval');

        switch ($interval) {
            case '0': // today
                $total_earnings = $this->calculateEarnings('today');
                break;

            case '1': // yesterday
                $total_earnings = $this->calculateEarnings('yesterday');
                break;

            case '2': // recent week
                $total_earnings = $this->calculateEarnings('recent_week');
                break;

            case '3': // last week
                $total_earnings = $this->calculateEarnings('last_week');
                break;

            case '4': // recent month
                $total_earnings = $this->calculateEarnings('recent_month');
                break;

            case '5': // last month
                $total_earnings = $this->calculateEarnings('last_month');
                break;

            case '6': // recent year
                $total_earnings = $this->calculateEarnings('recent_year');

                break;
            case '7': // last year
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
            case 'today':
                $today= Carbon::now()->startOfDay();
                $endOfDay=Carbon::now()->endOfDay();
                $result=CalculateTotalHourIncome::calculateHourIncome($today,$endOfDay);
                return $result;
                break;

            case 'yesterday':
                $today= Carbon::now()->subDay()->startOfDay();
                $endOfDay=Carbon::now()->subDay()->endOfDay();
                $result=CalculateTotalHourIncome::calculateHourIncome($today,$endOfDay);
                return $result;
                break;


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
