<?php

namespace App\Http\Services\Api;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CalculateTotalMonthIncome
{
    public static function calculateMonthIncome($startMonth, $endMonth)
    {
        $recentMonth=Carbon::now()->month();
        $recentYear = Carbon::now()->year;
        $total_earnings_for_week=0;
        $total_tax_for_week=0;
        $commission_for_week=0;
        $total_earning=0;
        $total_earning_week=[];
        $total_earnings_for_week = SubOrder::whereBetween('created_at',[$startMonth,$endMonth])
                ->whereIn('status', [2, 3])
                ->where('provider_id',Auth::user()->id)
                ->where('payment_status', 'complete')
                ->selectRaw('
                    FLOOR((DAY(created_at) - 1) / 7) + 1 as week,
                    SUM(total) as total_earnings
                ')
                ->groupBy('week')
                ->pluck('total_earnings', 'week');




        $total_tax_for_week =  SubOrder::whereBetween('created_at',[$startMonth,$endMonth])
            ->whereIn('status', [2, 3])
            ->where('provider_id',Auth::user()->id)
            ->where('payment_status', 'complete')
            ->selectRaw('
                FLOOR((DAY(created_at) - 1) / 7) + 1 as week,
                SUM(tax) as total_tax
            ')
            ->groupBy('week')
            ->pluck('total_tax', 'week');



        for($i = 1; $i <=5;$i++)
        {
            $total_earnings=$total_earnings_for_week[$i] ?? 0;
            $total_tax=$total_tax_for_week[$i] ?? 0;
            $total_earnings = ($total_earnings - $total_tax);
            $total_earning_week[$i] = [
                'week' => $i,
                'total_income' => $total_earnings
            ];
        }


        return $total_earning_week;
    }
}
