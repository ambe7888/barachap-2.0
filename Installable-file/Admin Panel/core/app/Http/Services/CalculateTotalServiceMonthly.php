<?php

namespace App\Http\Services;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use App\Models\Service;
use Carbon\Carbon;


class CalculateTotalServiceMonthly
{
    public static function calculateServiceMonthly($startMonth, $endMonth)
    {
        $weeks=["1","2","3","4","5"];
        $totalData=Service::whereBetween('created_at',[$startMonth,$endMonth])
        ->selectRaw('
            FLOOR((DAY(created_at) - 1) / 7) + 1 as week, 
            count(*) as total
        ')
        ->groupBy('week')
        ->pluck('total', 'week')->toArray();
        $data=[];
        foreach($weeks as $key=>$week)
        {
            if(array_key_exists($week,$totalData)){
                $data[$week]= $totalData[$week];

            }else{
                $data[$week]=0;
            }

            
        } 
        foreach($data as $key=>$value)
        {
            if($key=='1')
            {
                $data["Week 1"]=$value;
                unset($data[$key]);
            }
            else if($key=='2')
            {
                $data["Week 2"]=$value;
                unset($data[$key]);
            }
            else if($key=='3')
            {
                $data["Week 3"]=$value;
                unset($data[$key]);
            }
            else if($key=='4')
            {
                $data["Week 4"]=$value;
                unset($data[$key]);
            }
            else if($key=='5')
            {
                $data["Week 5"]=$value;
                unset($data[$key]);
            }
        }

        return $data;
    }
}
