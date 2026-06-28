<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Service;

class ClientReviewController extends Controller
{
    public function allclientReviews(){

        $client_id = Auth::guard('sanctum')->user()->id;
 
         $all_reviews = Review::with('reviewer', 'service','jobpost')->where('user_id', $client_id)->where('user_type','1')->latest()->paginate(10);
        
 
        return view('frontend.frontend.client.pages.reviews.all-list', compact('all_reviews'));  
 
     }

     //pagination
    public function pagination(Request $request)
    {
        if($request->ajax()){
            $client_id = Auth::guard('sanctum')->user()->id;
            $all_reviews = Review::with('reviewer', 'service','jobpost')->where('user_id', $client_id)->where('user_type','1')->latest()->paginate(10);
            return view('frontend.frontend.client.pages.reviews.search-result', compact('all_reviews'))->render();
        }
    }



     public function orderReviewAdd(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required',
            'order_id' => 'required|exists:orders,id',
            'sub_order_id' => 'required|exists:sub_orders,id',
            'service_id' => 'required|exists:services,id',
        ],[
            'rating.required' =>'Rating is required',
            'review.required' => 'Review is required',
            'order_id.required' => 'Order id is required',
            'sub_order_id' => 'Sub Order Id is required',
            'service_id' => 'Service id is required'
        ]);

         if ($validator->fails()) {
            return response()->json([
              'status' => 'validation_error',
              'errors' => $validator->errors(), // Send all errors
            ]);
        }

       
        $reviewer_id = Auth::guard('sanctum')->user()->id;
        $service=Service::where("id",$request->service_id)->first();
        $type = 'service';
        if($service?->provider_id)
        {
            $user_id = $service?->provider_id;
            $user_type=0; //provider
        }
        else if($service?->admin_id)
        {
            $user_id=$service?->admin_id;
            $user_type=2; //admin
        }
        
        $order_id = $request->order_id;
        $sub_order_id = $request->sub_order_id;
        
        

        $reviewer=User::where('id',$reviewer_id)->select('type')->first();
        $reviewer_type=$reviewer->type;

       
        $existingReview = Review::where('reviewer_id', $reviewer_id)
        ->where('user_id', $user_id)
        ->where('user_type', $user_type)
        ->where('order_id', $order_id)
        ->where('sub_order_id', $sub_order_id)
        ->exists();


        if ($existingReview) {
            return response()->json([
                'status' => "failed",
                'message' => __('You have already reviewed this service.')
            ], 200);
        }

    
        $review = Review::create([
            'user_id' => $user_id,
            'reviewer_id' => $reviewer_id,
            'rating' => $request->rating,
            'message' => $request->review,
            'order_id' => $order_id,
            'sub_order_id' => $sub_order_id,
            'service_id' => $request->service_id,
            'type' => $type,
            'user_type' => $user_type]);
      
       
        if ($review) {
            return response()->json([
                'status' => 'success',
                'message' => __('Review added successfully.')
            ],200);
        }
    }



}
