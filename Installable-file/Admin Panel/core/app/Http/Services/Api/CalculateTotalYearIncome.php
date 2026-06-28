<?php

namespace App\Http\Services\Api;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CalculateTotalYearIncome
{
    public static function calculateYearIncome($year)
    {
        $months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
        $total_earnings_for_month=0;
        $total_tax_for_month=0;
        $commission_for_month=0;
        $total_earning=0;
        $total_earning_month=[];
        foreach ($months as $month) {
                $total_earnings_for_month = SubOrder::whereIn('status', [2, 3])
                    ->where('provider_id',Auth::user()->id)
                ->where('payment_status', 'complete')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total');


            $total_tax_for_month = SubOrder::whereIn('status', [2, 3])
                ->where('provider_id',Auth::user()->id)
                ->where('payment_status', 'complete')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('tax');



            // Calculate total admin income for the year: earnings - tax + commission
            $total_earning = ($total_earnings_for_month - $total_tax_for_month);
            $total_earning_month[] = [
                'month' => $month,
                'total_income' => $total_earning
            ];

        }
        return $total_earning_month;
    }
}
