<?php

namespace Modules\JobPost\app\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\JobPost\app\Http\Requests\StoreJobRequest;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use Modules\JobPost\app\Resources\ClientJobOfferResource;
use Modules\JobPost\app\Resources\ClientJobResource;
use Modules\JobPost\app\Resources\JobOfferDetailsResource;
use Modules\JobPost\app\Resources\JobsResource;
use Modules\JobPost\app\Services\JobsService;

class ClientJobController extends Controller
{
    protected $jobsService;

    public function __construct(JobsService $jobsService)
    {
        $this->jobsService = $jobsService;
    }

    public function job_details($id)
    {
        $client_id = Auth::guard('sanctum')->user()->id;

        $job = JobPost::with(['job_offers', 'client'])
            ->where('id', $id)
            ->where('client_id', $client_id)
            ->first();


        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }
        // Update view count
        $job->increment('view');
        // Check if the job has been hired
        $is_job_hired = JobPostOffer::where('job_post_id', $job->id)->where('is_hired', 1)->count();

        // Return the job details and the hired status
        return response()->json([
            'job_details' => new JobsResource($job),
            'is_job_hired' => $is_job_hired,
        ]);
    }

    public function offers_list(Request $request)
    {

        if(!Auth::guard('sanctum')->check()){
            return response()->json([
                'msg'=>'Please log in to view job offers.',
            ], 403);
        }

        $job_id = $request->input('job_id');
        $status = $request->input('status');
        $is_hired = $request->input('is_hired');

        $client_id = auth('sanctum')->user()->id;
        $query = JobPostOffer::with('job', 'provider')
            ->where('client_id', $client_id);

        if (!empty($job_id)){
            $query->where('job_post_id', $job_id);
        }

        if ($status !== null){
          $query->where('status', $status);
        }

        if ($is_hired == 'hired'){
            $query->where('is_hired', 1);
        }


        $job_offers = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'job_offers' => $job_offers->isEmpty()
                ? ['message' => __("Job offers not found.")]
                : ClientJobOfferResource::collection($job_offers),
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

    public function offers_details($id)
    {
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;

        $job_offer = JobPostOffer::with('provider.userServiceLocation', 'job_location')
            ->where('id', $id)
            ->where('client_id', $client_id)
            ->first();

        if (!$job_offer) {
            return response()->json([
                'message' => __('Job offer not found'),
            ], 404);
        }

        return response()->json([
            'message' => new JobOfferDetailsResource($job_offer),
        ]);

    }

    public function offers_reject($id)
    {
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;
        $job_offer = JobPostOffer::where('id', $id)->where('client_id', $client_id)->first();

        if (!$job_offer) {
            return response()->json([
                'message' => __('Job offer not found'),
            ], 404);
        }

        if ($job_offer->is_hired === 1 && $job_offer->status === 1) {
            return response()->json([
                'message' => __('Already Hired Provider'),
            ], 300);
        }

        // job publication status
        $job_offer->status = 2;
        $job_offer->save();

        return response()->json([
            'message' => __('Job Offer rejected successfully.'),
        ]);

    }


    public function job_published_status($id)
    {
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPost::where('id', $id)->where('client_id', $client_id)->first();

        if (!$job) {
            return response()->json([
                'message' => __('Job not found'),
            ], 404);
        }

       // Check if the job status is inactive
        if ($job->status == 0) {
            return response()->json([
                'message' => __('The job is currently inactive. Please wait for the admin to activate it before you can publish.'),
            ], 403);
        }

        // job publication status
        $job->is_published = !$job->is_published;
        $job->published_at = now();
        $job->save();

        // Show appropriate message
        if ($job->is_published) {
            return response()->json([
                'message' => __('Job has been successfully published'),
            ]);
        } else {
            return response()->json([
                'message' => __('Job has been successfully unpublished.'),
            ]);
        }
    }

    public function delete_job($id = null)
    {

        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPost::where('id', $id)->where('client_id', $client_id)->first();

        if(!empty($job)){
            JobPostOffer::where('job_post_id',$id)->delete();
            $job->delete();
            return response()->json([
                'msg' => __('Job Post Deleted Success'),
            ]);
        }else{
            return response()->json([
                'msg' => __('Job Id Not Found'),
            ], 404);
        }
    }

    public function offer_delete($id = null)
    {

        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPostOffer::where('id', $id)->where('client_id', $client_id)->first();

        if(!empty($job)){
            $job->delete();
            return response()->json([
                'msg' => __('Job Offer Deleted Success'),
            ]);
        }else{
            return response()->json([
                'msg' => __('Job Offer Id Not Found'),
            ], 404);
        }
    }

    public function job_list(Request $request)
    {
        $client_id = auth('sanctum')->user()->id;
        $title = trim(strip_tags($request->input('title')));
        $status = $request->input('status');
        $is_published = $request->input('is_published');

        $query = JobPost::with('job_offers')->where('client_id',$client_id);

         if($title){
             $query->where('title', 'like', '%' . $title . '%');
         }

        if ($status !== null) {
            $query->where('status', intval($status));
        }

        if ($is_published !== null) {
            $query->where('is_published', intval($is_published));
        }

        $jobs = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'jobs' => $jobs->isEmpty()
                ? ['message' => __("No Job Found")]
                : ClientJobResource::collection($jobs),
            'pagination' => [
                'total' => $jobs->total(),
                'count' => $jobs->count(),
                'per_page' => $jobs->perPage(),
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'next_page_url' => $jobs->nextPageUrl(),
                'prev_page_url' => $jobs->previousPageUrl(),
            ]
        ], $jobs->isEmpty() ? 200 : 200);

    }

    public function create_job(StoreJobRequest $request)
    {

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth_user = Auth::guard('sanctum')->user();
        if ($auth_user->type === 0) {
            return response()->json([
                'message' => __('Job creation is only allowed for clients.'),
            ],300);
        }

        $request->validated();
        $this->jobsService->createJob($request);
        return response()->json([
            'message' => __('Job Successfully Created'),
        ]);
    }

    public function edit_job(StoreJobRequest $request, $jobId)
    {

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth_user = Auth::guard('sanctum')->user();
        if ($auth_user->type === 0) {
            return response()->json([
                'message' => __('Job creation is only allowed for clients.'),
            ],300);
        }

        $request->validated();
        $this->jobsService->editJob($request, $jobId);
        return response()->json([
            'message' => __('Job Successfully Created'),
        ]);
    }
}
