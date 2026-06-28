<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserReviewController extends Controller
{
    public function orderReviewAdd(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => __('Please login to review this user.')
            ], 422);
        }


        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'rating' => 'required|numeric|min:1|max:5',
                'message' => 'required',
                'order_id' => 'required|exists:orders,id',
                'sub_order_id' => 'required|exists:sub_orders,id',
                'user_type' => 'required|between:0,2',
                'service_id' => 'nullable|required_without:job_id',
                'job_id' => 'nullable|required_without:service_id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->errors(),
            ], 422);
        }

        $reviewer_id = Auth::guard('sanctum')->user()->id;
        $user_id = $request->user_id;
        $order_id = $request->order_id;
        $sub_order_id = $request->sub_order_id;
        $user_type=$request->user_type;
        $type = null;


        $reviewer=User::where('id',$reviewer_id)->select('type')->first();
        $reviewer_type=$reviewer->type;

        if($reviewer_type == '1'  &&  $user_type == '1')
        {
             return response()->json([
                'message' => __('As you are a client so you can only submit review to provider or admin.')
            ], 422);
        }
        else if($reviewer_type == '0' && $user_type == '0')
        {
             return response()->json([
                'message' => __('As you are a provider so you can only submit review to client')
            ], 422);
        }


        $existingReview = Review::where('reviewer_id', $reviewer_id)
        ->where('user_id', $user_id)
        ->where('user_type', $user_type)
        ->where('order_id', $order_id)
        ->where('sub_order_id', $sub_order_id)
        ->exists();

        if ($user_id == $reviewer_id && $reviewer_type == $user_type) {
            return response()->json([
                'message' => __('You cannot review yourself.')
            ], 422);
        }

        if ($existingReview) {
            return response()->json([
                'message' => __('You have already reviewed this user.')
            ], 422);
        }
        if ($request->service_id && $request->job_id) {
            return response()->json([
                'message' => __('Please provide either service_id or job_id, not both.')
            ]);
        }

        if ($request->service_id) {
            $type = 'service';
        } elseif ($request->job_id) {
            $type = 'job';
        }



        $review = Review::create([
            'user_id' => $user_id,
            'reviewer_id' => $reviewer_id,
            'rating' => $request->rating,
            'message' => $request->message,
            'order_id' => $order_id,
            'sub_order_id' => $sub_order_id,
            'service_id' => $request->service_id ?? null,
            'job_id' => $request->job_id ?? null,
            'type' => $type,
            'user_type' => $user_type]);


        if ($review) {
            return response()->json([
                'status' => 'add_success',
                'message' => __('Review added successfully.')
            ]);
        }
    }

}
