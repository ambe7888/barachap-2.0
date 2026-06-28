<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OrderCancellationPolicy;
use Illuminate\Http\Request;
use App\Helpers\FlashMsg;

class OrderCancellationPolicyController extends Controller
{
    public function cancellationPolicyPage()
    {
        $cancellationPolicy = OrderCancellationPolicy::first();
        return view('backend.pages.orders.order_cancellation_policy.add', compact('cancellationPolicy'));
    }

    public function cancellationPolicy(Request $request)
    {
        $request->validate([
            'fine_type' => 'required|in:percentage,flat',
            'amount' => 'required|numeric',
            'available_type' => 'required|in:always,certain_time',

          
        ], [
            'amount.required' => __("Amount is required"),  
            'amount.numeric' => __("Amount must be a number"),
            'fine_type.required' => __("Fine Type is required"),
            'fine_type.in' => __("Fine Type must be either percentage or flat"),
            'available_type.required' => __("Available Type is required"),
            'available_type.in' => __("Available Type must be either always or certain_time"),
        ]);

        $cancellationPolicy = OrderCancellationPolicy::first();

        if ($cancellationPolicy) {
            
            OrderCancellationPolicy::truncate();
            
        } 
        
       
        $cancellationPolicy = OrderCancellationPolicy::create([
            'fine_type' => $request->fine_type,
            'amount' => $request->amount,
            'available_type' => $request->available_type,
            'time_in_min'=>$request->time_in_min ?? 1,
            'description' => $request->description ?? null
        ]);

        if($cancellationPolicy)
        {
            return redirect()->back()->with(FlashMsg::item_new(__("Order Cancellation Policy updated Successfully")));
        }
        else
        {
            return redirect()->back()->with(FlashMsg::item_delete(__("Order Cancellation Policy not updated")));
        }
        
      
    
    }
}
