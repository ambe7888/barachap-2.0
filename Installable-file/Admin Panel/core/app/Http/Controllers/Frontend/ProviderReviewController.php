<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Models\Review;


class ProviderReviewController extends Controller
{
    public function allProviderReviews(){

        $provider_id = Auth::guard('sanctum')->user()->id;
 
         $all_reviews = Review::with('reviewer', 'service','jobpost')->where('user_id', $provider_id)->where('user_type','0')->latest()->paginate(10);
        
 
        return view('frontend.frontend.provider.pages.reviews.all-list', compact('all_reviews'));  
 
     }

}
