<?php

namespace Modules\JobPost\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use Modules\JobPost\app\Resources\JobOfferResource;
use Modules\JobPost\app\Resources\ProviderJobOfferResource;

class ProviderJobOfferController extends Controller
{

    public function job_offers_lists(Request $request)
    {
        if(!Auth::guard('sanctum')->check()){
            return response()->json([
                'msg'=> __('Please log in to view job offers.'),
            ]);
        }

        $provider_id = auth('sanctum')->user()->id;
        $query = JobPostOffer::with('job')->where('provider_id', $provider_id);
        $job_offers = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'job_offers' => $job_offers->isEmpty()
                ? ['message' => __("Job offers not found yet")]
                : ProviderJobOfferResource::collection($job_offers),
            'pagination' => [
                'total' => $job_offers->total(),
                'count' => $job_offers->count(),
                'per_page' => $job_offers->perPage(),
                'current_page' => $job_offers->currentPage(),
                'last_page' => $job_offers->lastPage(),
                'next_page_url' => $job_offers->nextPageUrl(),
                'prev_page_url' => $job_offers->previousPageUrl(),
            ]
        ], $job_offers->isEmpty() ? 200 : 200);

    }
    public function job_offer_create(Request $request){

        $provider_id = auth('sanctum')->user()->id;
        $user_type = auth('sanctum')->user()->type;

        if(Auth::guard('sanctum')->check() && $user_type === 1){
            return response()->json([
                'msg'=> __('For create an offer you must register as a provider'),
            ], 403);
        }

        $job = JobPost::find($request->job_post_id);

        if (empty($job)){
            return response()->json([
                'msg'=>'Job not found',
            ], 404);
        }

            if(Auth::guard('sanctum')->check()){
                $request->validate([
                    'job_post_id'=> 'required',
                    'budget' => 'required|numeric',
                    'cover_letter'=> 'nullable',
                ]);

                $provider_request_count = JobPostOffer::where('provider_id',$provider_id)
                    ->where('job_post_id',$request->job_post_id)
                    ->count();

                if($provider_request_count >=1){
                    return response()->json([
                        'msg'=>'You have already offer for this job.',
                    ], 400);
                }

                JobPostOffer::create([
                    'provider_id'=> $provider_id,
                    'client_id'=> $job->client_id,
                    'job_post_id'=> $job->id,
                    'budget'=> $request->budget,
                    'cover_letter'=> $request->cover_letter,
                ]);

                try {
                    // get client email
                    $client_email = User::select('id', 'email')
                        ->where('id', $job->client_id)
                        ->first();

                    $message_body = __('New Offer is created for your job').'. '.'<span class="verify-code">'.__('Your job id is').' #'.$job->id.'</span>';
                    Mail::to($client_email->email)->send(new BasicMail([
                        'subject' => __('New Job Offer Created'),
                        'message' => $message_body
                    ]));
                } catch (\Exception $e) {}

                return response()->json([
                    'msg'=>'You have successfully made an offer for this job.',
                ]);

            }else{
                return response()->json([
                    'msg'=>'You must login to apply for a job.',
                ], 401);
            }

    }
}
