<?php

namespace App\Http\Services;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use Carbon\Carbon;


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
                ->whereNotNull('admin_id')
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
            ->whereNotNull('admin_id')
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
            
            
        
        
        $commission_for_day = SubOrder::whereBetween('created_at',[$startWeek,$endWeek])
            ->whereIn('status', [2, 3])
            ->whereNotNull('provider_id')
            ->where('payment_status', 'complete')
            ->get()
            ->groupBy(function($item)
            {
                return $item->created_at->format("l");
            })
            ->map(function($group)
            {
                return $group->sum("commission_amount");
            });


        
        $commissionData=$commission_for_day->toArray();
        $finalCommissionData=[];
        foreach($daysOfWeek as $key=> $dayName)
        {
            if(array_key_exists($dayName,$commissionData)){
                $finalCommissionData[$dayName]=$commissionData[$dayName];

            }else{
                $finalCommissionData[$dayName]=0;
            }
        }  
           
        foreach($daysOfWeek as $key=> $dayName)
        {
            $total_earnings=$finalBarData[$dayName] ?? 0;
            $total_tax=$finalTaxData[$dayName] ?? 0;
            $commission=$finalCommissionData[$dayName] ?? 0;
            $total_earnings = ($total_earnings - $total_tax) + $commission;
            $total_earning_day[] = [
                'day' =>$dayName,
                'total_income' => $total_earnings
            ];
        }
        

        return $total_earning_day;
    }
}
