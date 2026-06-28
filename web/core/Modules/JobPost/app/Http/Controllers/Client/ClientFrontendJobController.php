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
use Modules\JobPost\app\Services\ClientJobsService;
use App\Helpers\FlashMsg;
use App\Models\UserNotification;
use App\Models\Backend\Category;
use App\Models\UserLocation;
use Modules\CountryManage\app\Models\State;
use Illuminate\Support\Facades\Validator;


class ClientFrontendJobController extends Controller
{
    protected $jobsService;

    public function __construct(ClientJobsService $jobsService)
    {
        $this->jobsService = $jobsService;
    }

    public function job_details($id,$notificationId=null)
    {

        $client_id = Auth::guard('sanctum')->user()->id;

        $job = JobPost::with(['job_offers', 'client'])
            ->where('id', $id)
            ->where('client_id', $client_id)
            ->first();


        if (!$job) {

            return redirect()->back()->with(FlashMsg::item_delete('Job not found'));

        }
        // Update view count
        $job->increment('view');
        // Check if the job has been hired
        $is_job_hired = JobPostOffer::where('job_post_id', $job->id)->where('is_hired', 1)->count();

        try {
            UserNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        }catch (\Exception $exception){}

        // Return the job details and the hired status

        return view('jobpost::frontend.client.job-details', compact('job', 'is_job_hired'));

    }

    public function offers_list(Request $request,$job_id = null)
    {

        $client_id = auth('sanctum')->user()->id;
        $query = JobPostOffer::with('job', 'provider')
            ->where('client_id', $client_id);

        if (!empty($job_id)){
            $query->where('job_post_id', $job_id);
        }


        $all_offers = $query->latest()->paginate(10);

        return view('jobpost::frontend.client.job-offer.all-list', compact('all_offers'));
    }

    public function offers_details($id)
    {
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;

        $job_post_offer = JobPostOffer::with('provider.userServiceLocation', 'job_location')
            ->where('id', $id)
            ->where('client_id', $client_id)
            ->first();

        $type="job";
        $job_location_id=$job_post_offer->job_location->id;
        $tax=calculateTaxBasedOnCoordinates($job_location_id, $type);
        // tax calculate
        $tax = ($job_post_offer->budget * $tax) / 100;

        if (!$job_post_offer) {

            return redirect()->back()->with(FlashMsg::item_delete('Job offer not found'));

        }
        $job=JobPost::where('id',$job_post_offer->job_post_id)->first();

        return view('jobpost::frontend.client.job-offer.job-details', compact('job_post_offer','job','tax'));

    }

    public function offers_reject(Request $request)
    {
        $id = $request->input('job_offer_id');
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;
        $job_offer = JobPostOffer::where('id', $id)->where('client_id', $client_id)->first();

        if (!$job_offer) {
            return redirect()->back()->with(FlashMsg::item_delete('Job offer not found'));
        }

        if ($job_offer->is_hired === 1 && $job_offer->status === 1) {

            return redirect()->back()->with(FlashMsg::item_delete('ALready Hired Provider'));
        }

        // job publication status
        $job_offer->status = 2;
        $job_offer->save();

        return redirect()->back()->with(FlashMsg::item_new('Job Offer rejected successfully.'));


    }


    public function job_published_status($id)
    {
        // First check if the job exists
        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPost::where('id', $id)->where('client_id', $client_id)->first();

        if (!$job) {

            return redirect()->back()->with(FlashMsg::item_delete('Job not found'));

        }

       // Check if the job status is inactive
        if ($job->status == 0) {

            return redirect()->back()->with(FlashMsg::item_delete('The job is currently inactive. Please wait for the admin to activate it before you can publish.'));

        }

        // job publication status
        $job->is_published = !$job->is_published;
        $job->published_at = now();
        $job->save();

        // Show appropriate message
        if ($job->is_published) {

            return redirect()->back()->with(FlashMsg::item_new('Job has been successfully published'));

        } else {

            return redirect()->back()->with(FlashMsg::item_new('Job has been successfully unpublished.'));

        }
    }

    public function delete_job($id = null)
    {

        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPost::where('id', $id)->where('client_id', $client_id)->first();

        if(!empty($job)){
            JobPostOffer::where('job_post_id',$id)->delete();
            $job->delete();
            return redirect()->back()->with(FlashMsg::item_new('Job Post Deleted Success'));

        }else{
           return redirect()->back()->with(FlashMsg::item_new('Job Id Not Found'));

        }
    }

    public function offer_delete($id = null)
    {

        $client_id = Auth::guard('sanctum')->user()->id;
        $job = JobPostOffer::where('id', $id)->where('client_id', $client_id)->first();

        if(!empty($job)){
            $job->delete();
            return redirect()->back()->with(FlashMsg::item_delete('Job Offer Deleted Success'));
        }else{

            return redirect()->back()->with(FlashMsg::item_delete('Job Offer Id Not Found'));

        }
    }

    public function job_list(Request $request)
    {

        $client_id = auth('sanctum')->user()->id;


        $all_jobs = JobPost::with('job_offers')->where('client_id',$client_id)->where('status',1)->latest()->paginate(10);

        return view('jobpost::frontend.client.all-jobs', compact('all_jobs'));
    }

    public function create_job(Request $request)
    {
        // dd("sdjsd");
        if($request->isMethod('post')){

            $auth_user = Auth::guard('sanctum')->user();
            if ($auth_user->type === 0) {

                return redirect()->route('client.jobs.create')->with(FlashMsg::item_new('Job creation is only allowed for clients.'));

            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|integer|exists:categories,id',
                'budget' => 'required|numeric|min:0',
                'gallery_images' => 'nullable|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
            ]);
            //if from above give validation error then return to create job page

            if($validator->fails()){
                return redirect()->route('client.jobs.create')->withErrors($validator)->withInput();
            }


            $this->jobsService->createJob($request);

            return redirect()->route('client.jobs.create')->with(FlashMsg::item_new('Job Created Successfully'));


        }

        $category=Category::where('status',1)->get();
        $user_location=UserLocation::where('user_id',auth('sanctum')->user()->id)->get();

        return view('jobpost::frontend.client.create-job',compact('category','user_location'));
    }

    public function edit_client_job(Request $request, $jobId)
    {

       if($request->isMethod('post')){


            $auth_user = Auth::guard('sanctum')->user();
            if ($auth_user->type === 0) {

                return redirect()->route('client.jobs.edit')->with(FlashMsg::item_new('Job creation is only allowed for clients.'));

            }

            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|integer|exists:categories,id',
                'budget' => 'required|numeric|min:0',
                'gallery_images' => 'nullable|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
            ]);
            $this->jobsService->editJob($request, $jobId);

            return redirect()->route('client.jobs.edit', ['id' => $jobId])->with(FlashMsg::item_new('Job Updated Successfully'));

       }

        $client_id = auth('sanctum')->user()->id;
        $job = JobPost::where('id', $jobId)->where('client_id', $client_id)->with('job_location')->first();
        if (!$job) {
            return redirect()->route('client.jobs.edit',['id' => $jobId])->with(FlashMsg::item_delete('Job not found'));
        }
        $category=Category::where('status',1)->get();
        $user_location=UserLocation::where('user_id',auth('sanctum')->user()->id)->get();

        return view('jobpost::frontend.client.edit-job', compact('job','category','user_location'));


    }

    public function searchJob(Request $request)
    {
        $client_id = auth('sanctum')->user()->id;
        $all_jobs = JobPost::where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->where('client_id',$client_id)->where('status',1)->latest()->paginate(10);
        return $all_jobs->total() >= 1 ? view('jobpost::frontend.client.search-result',
            compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }


    //job pagination
    public function paginateJob(Request $request)
    {
        $client_id = auth('sanctum')->user()->id;
        $all_jobs = JobPost::where('client_id',$client_id)->where('status',1)->latest()->paginate(10);
        return $all_jobs->total() >= 1 ? view('jobpost::frontend.client.search-result',
            compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //job offer pagination
    public function paginateJobOffer(Request $request)
    {
        $client_id = auth('sanctum')->user()->id;
        $all_offers = JobPostOffer::where('client_id',$client_id)->latest()->paginate(10);
        return $all_offers->total() >= 1 ? view('jobpost::frontend.client.job-offer.search-result',
            compact('all_offers'))->render() : response()->json(['status'=>__('nothing')]);
    }



}
