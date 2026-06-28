<?php

namespace App\Http\Services;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use App\Models\User;
use Carbon\Carbon;


class CalculateTotalUserWeekly
{
    public static function calculateUserWeekly($startWeek, $endWeek)
    {
        $daysOfWeek =['2','3','4','5','6','7','1'];
        // Example logic to fetch weekly user data
        $totalData = User::selectRaw('DAYOFWEEK(created_at) as day, count(*) as total')
        ->whereBetween('created_at', [$startWeek, $endWeek]) 
        ->groupBy('day')
        ->pluck('total', 'day')->toArray();
        $data=[];
        foreach($daysOfWeek as $key=>$day)
        {
            
            if(array_key_exists($day,$totalData)){
                $data[$day]= $totalData[$day];

            }else{
                $data[$day]=0;
            }

        }  
        foreach($data as $key=>$value)
        {
            if($key=='1')
            {
                $data["Sunday"]=$value;
                unset($data[$key]);
            }
            else if($key=='2')
            {
                $data["Monday"]=$value;
                unset($data[$key]);
            }
            else if($key=='3')
            {
                $data["Tuesday"]=$value;
                unset($data[$key]);
            }
            else if($key=='4')
            {
                $data["Wednesday"]=$value;
                unset($data[$key]);
            }
            else if($key=='5')
           {
                $data["Thursday"]=$value;
                unset($data[$key]);
            }
            else if($key=='6')
            {
                $data["Friday"]=$value;
                unset($data[$key]);
            }
            else if($key=='7')
            {
                $data["Saturday"]=$value;
                unset($data[$key]);
            }
        }
      

        return $data;
    }
}
