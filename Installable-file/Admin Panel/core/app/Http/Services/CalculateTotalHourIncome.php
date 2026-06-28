<?php

namespace App\Http\Services;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;


class CalculateTotalHourIncome
{
    public static function calculateHourIncome($startOfDay, $endOfDay)
    {
        $hoursOfDay = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
        $total_earnings_for_hour=0;
        $total_tax_for_hour=0;
        $commission_for_hour=0;
        $total_earning=0;
        $total_earning_hour=[];
        $total_earnings_for_hour= SubOrder::whereBetween('created_at',[$startOfDay,$endOfDay])
                ->whereIn('status', [2, 3])
                ->whereNotNull('admin_id')
                ->where('payment_status', 'complete')
                ->get()
                ->groupBy(function($item)
                {
                    return $item->created_at->format("H");
                })
                ->map(function($group)
                {
                    return $group->sum("total");
                });
        
        $barData=$total_earnings_for_hour->toArray();
        $finalBarData=[];
        foreach($hoursOfDay as $key=> $hourName)
        {
            if(array_key_exists($hourName,$barData)){
                $finalBarData[$hourName]=$barData[$hourName];

            }else{
                $finalBarData[$hourName]=0;
            }
        }

        
              
        $total_tax_for_hour =  SubOrder::whereBetween('created_at',[$startOfDay,$endOfDay])
            ->whereIn('status', [2, 3])
            ->whereNotNull('admin_id')
            ->where('payment_status', 'complete')
            ->get()
            ->groupBy(function($item)
            {
                return $item->created_at->format("H");
            })
            ->map(function($group)
            {
                return $group->sum("tax");
            });

            $taxData=$total_tax_for_hour->toArray();
            $finalTaxData=[];
            foreach($hoursOfDay as $key=> $hourName)
            {
                if(array_key_exists($hourName,$taxData)){
                    $finalTaxData[$hourName]=$taxData[$hourName];

                }else{
                    $finalTaxData[$hourName]=0;
                }
            }  
            
            
        
        
        $commission_for_hour =  SubOrder::whereBetween('created_at',[$startOfDay,$endOfDay])
            ->whereIn('status', [2, 3])
            ->whereNotNull('provider_id')
            ->where('payment_status', 'complete')
            ->get()
            ->groupBy(function($item)
            {
                return $item->created_at->format("H");
            })
            ->map(function($group)
            {
                return $group->sum("commission_amount");
            });

        
        $commissionData=$commission_for_hour->toArray();
        $finalCommissionData=[];
        foreach($hoursOfDay as $key=> $hourName)
        {
            if(array_key_exists($hourName,$commissionData)){
                $finalCommissionData[$hourName]=$commissionData[$hourName];

            }else{
                $finalCommissionData[$hourName]=0;
            }
        }  
           
        foreach($hoursOfDay as $key=> $hourName)
        {
            $total_earnings=$finalBarData[$hourName] ?? 0;
            $total_tax=$finalTaxData[$hourName] ?? 0;
            $commission=$finalCommissionData[$hourName] ?? 0;
            $total_earnings = ($total_earnings - $total_tax) + $commission;
            $total_earning_day[] = [
                'day' =>$hourName,
                'total_income' => $total_earnings
            ];
        }
       

        return $total_earning_day;
    }
}
