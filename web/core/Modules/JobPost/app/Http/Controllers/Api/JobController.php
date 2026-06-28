<?php

namespace Modules\JobPost\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use Modules\JobPost\app\Resources\ClientJobResource;
use Modules\JobPost\app\Resources\JobDetailsPublicResource;
use Modules\JobPost\app\Resources\JobDetailsResource;
use Modules\JobPost\app\Resources\JobListsPublicResource;
use Modules\JobPost\app\Resources\JobOfferPublicResource;
use Modules\JobPost\app\Resources\JobsResource;


class JobController extends Controller
{
    public function job_details($id)
    {
        $job = JobPost::with(['reviews', 'job_offers', 'client'])->find($id);
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }

        $is_job_hired = JobPostOffer::where('job_post_id', $job->id)->where('is_hired', 1)->count();

        $provider_offer = null;
        if (Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->type === 0){
                $provider_offer = JobPostOffer::where('job_post_id', $job->id)
                    ->where('provider_id', Auth::guard('sanctum')->user()->id)
                    ->first();
        }else{
            // Update view count
            $job->increment('view');
        }


        return response()->json([
            'job_details' => new JobDetailsPublicResource($job),
            'is_hired' => $is_job_hired,
            'job_offers' => $provider_offer ? new JobOfferPublicResource($provider_offer) : null,
        ]);
    }

    public function job_list(Request $request)
    {

        $title = trim(strip_tags($request->input('title')));
        $budget = $request->input('budget');
        $category_id = $request->input('category_id');
        $applied_jobs  = $request->input('applied_jobs');
        $saved_jobs  = $request->input('saved_jobs');

        $query = JobPost::where('status', 1)->where('is_published', 1);

        // Get all job_post IDs where is_hired = 1 and exclude them
        $job_post_offer_id_pluck = JobPostOffer::where('is_hired', 1)
            ->pluck('job_post_id');

        $query->whereNotIn('id', $job_post_offer_id_pluck);


        if($title){
            $query->where('title', 'like', '%' . $title . '%');
        }

        if($budget){
            $query->where('budget', $budget);
        }

        if (!empty($budget) && is_numeric($budget)) {
            // Filter jobs where the budget is less than or equal to the specified budget
            $query->where('budget', '<=', $budget);
        }

        if($category_id){
            $query->where('category_id', $category_id);
        }

        if (auth('sanctum')->check() && auth('sanctum')->user()->type === 0) {
            $provider_id = auth('sanctum')->user()->id;

            // provider applied jobs
            if ($applied_jobs == 1){
                $job_post_ids = JobPostOffer::where('provider_id', $provider_id)->pluck('job_post_id');
                $job_post_ids = $job_post_ids->toArray();
                $query->whereIn('id', $job_post_ids);
            }

            // provider saved jobs
            if ($saved_jobs == 1){
                $job_post_ids = FavoriteItem::where('user_id', $provider_id)->where('type', 'job')->pluck('item_id');
                $job_post_ids = $job_post_ids->toArray();
                $query->whereIn('id', $job_post_ids);
            }
        }

        $jobs = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'jobs' => $jobs->isEmpty()
                ? ['message' => __("No Job Found")]
                : JobListsPublicResource::collection($jobs),
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

}
