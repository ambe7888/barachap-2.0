<?php

namespace App\Http\Services\provider;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CalculateTotalWeekIncome
{
    public static function calculateWeekIncome($startWeek, $endWeek)
    {
        $daysOfWeek = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday","Sunday");
        $recentMonth=Carbon::now()->month();
        $recentYear = Carbon::now()->year();
        $total_earnings_for_day=0;
        $total_tax_for_day=0;
        $commission_for_day=0;
        $total_earning=0;
        $total_earning_day=[];
        $total_earnings_for_day = SubOrder::whereBetween('created_at',[$startWeek,$endWeek])
                ->whereIn('status', [2, 3])
                ->where('provider_id',Auth::user()->id)
                ->where('payment_status', 'complete')
                ->get()
                ->groupBy(function($item)
                {
                    return $item->created_at->format("l");
                })
                ->map(function($group)
                {
                    return $group->sum("total");
                });


        $barData=$total_earnings_for_day->toArray();
        $finalBarData=[];
        foreach($daysOfWeek as $key=> $dayName)
        {
            if(array_key_exists($dayName,$barData)){
                $finalBarData[$dayName]=$barData[$dayName];

            }else{
                $finalBarData[$dayName]=0;
            }
        }



        $total_tax_for_day =  SubOrder::whereBetween('created_at',[$startWeek,$endWeek])
            ->whereIn('status', [2, 3])
            ->where('provider_id',Auth::user()->id)
            ->where('payment_status', 'complete')
            ->get()
            ->groupBy(function($item)
            {
                return $item->created_at->format("l");
            })
            ->map(function($group)
            {
                return $group->sum("tax");
            });


            $taxData=$total_tax_for_day->toArray();
            $finalTaxData=[];
            foreach($daysOfWeek as $key=> $dayName)
            {
                if(array_key_exists($dayName,$taxData)){
                    $finalTaxData[$dayName]=$taxData[$dayName];

                }else{
                    $finalTaxData[$dayName]=0;
                }
            }



        foreach($daysOfWeek as $key=> $dayName)
        {
            $total_earnings=$finalBarData[$dayName] ?? 0;
            $total_tax=$finalTaxData[$dayName] ?? 0;
            $total_earnings = ($total_earnings - $total_tax);
            $total_earning_day[] = [
                'day' =>$dayName,
                'total_income' => $total_earnings
            ];
        }


        return $total_earning_day;
    }
}
