<?php

namespace App\Http\Services;


use App\Helpers\FlashMsg;
use App\Models\SubOrder;
use App\Models\Service;
use Carbon\Carbon;


class CalculateTotalServiceYearly
{
    public static function calculateServiceYearly($currentYear)
    {
        $months=['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                // Example logic to fetch monthly Service data
                $totalData = Service::selectRaw('MONTH(created_at) as month, count(*) as total')
                    ->whereYear('created_at',$currentYear)
                    ->groupBy('month')
                    ->pluck('total', 'month')->toArray();
                $data=[];
                foreach($months as $key=>$month)
                {
                    if(array_key_exists($month,$totalData)){
                        $data[$month]=$totalData[$month];
        
                    }else{
                        $data[$month]=0;
                    }
                }  
                foreach($data as $key=>$value)
                {
                    if($key=='1')
                    {
                        $data["Jan"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='2')
                    {
                        $data["Feb"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='3')
                    {
                        $data["Mar"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='4')
                    {
                        $data["April"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='5')
                   {
                        $data["May"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='6')
                    {
                        $data["Jun"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='7')
                    {
                        $data["July"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='8')
                    {
                        $data["Aug"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='9')
                    {
                        $data["Sep"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='10')
                    {
                        $data["Oct"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='11')
                    {
                        $data["Nov"]=$value;
                        unset($data[$key]);
                    }
                    else if($key=='12')
                    {
                        $data["Dec"]=$value;
                        unset($data[$key]);
                    }
                }
      

        return $data;
    }
}
