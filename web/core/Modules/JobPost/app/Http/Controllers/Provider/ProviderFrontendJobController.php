<?php

namespace Modules\JobPost\app\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use App\Models\FavoriteItem;
use App\Helpers\FlashMsg;
use Illuminate\Support\Facades\Validator;

class ProviderFrontendJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $status = $request->input('status', 'all');

        $query = JobPost::where('status', 1)->where('is_published', 1);
        
       
        $provider_id = Auth::user()->id;
        if($status == 1)//applied job
        {
 
            // provider applied jobs
            $job_post_ids = JobPostOffer::where('provider_id', $provider_id)->pluck('job_post_id');
            $job_post_ids = $job_post_ids->toArray();
            $query->whereIn('id', $job_post_ids);
        }
        else if($status == 0)//saved job
        {
            //collect saved job
            $job_post_ids = FavoriteItem::where('user_id', $provider_id)->where('type', 'job')->pluck('item_id');
            $job_post_ids = $job_post_ids->toArray();
            $query->whereIn('id', $job_post_ids);
        }
        else
        {
            // Get all job_post IDs where is_hired = 1 and exclude them
            $job_post_offer_id_pluck = JobPostOffer::where('is_hired', 1)
            ->pluck('job_post_id');

             $query->whereNotIn('id', $job_post_offer_id_pluck);
    
        }
      
        $all_jobs = $query->latest()->paginate(10);
        $current_status=$status;
       
        return view('jobpost::frontend.jobs.all-jobs',compact('all_jobs','current_status'));    

    }
    public function jobDetails($id,$notificationId = null)
    {
        $job = JobPost::with('client', 'job_location')
            ->where('id', $id)
            ->latest()
            ->first();

        if (!$job) {
            abort(404);
        }

        $provider_id=Auth::user()->id;
        $job_post_offer = JobPostOffer::where('provider_id', $provider_id)->where("job_post_id",$job->id)->first();
       
        if($job_post_offer)
        {
            $job->send_offer=1;
        }
        else
        {
            $job->send_offer=0;
        }

        try {
            // AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        }catch (\Exception $exception){}

        return view('jobpost::frontend.jobs.job-details',compact('job','job_post_offer'));
    }
  
    

    public function job_offer_create(Request $request,$job_post_id){

        if ($request->isMethod('post')) {

            $provider_id = Auth::user()->id;
            $user_type = Auth::user()->type;

            if(Auth::guard('sanctum')->check() && $user_type === 1){
                return back()->with(FlashMsg::item_delete(__('For create an offer you must register as a provider')));
            }

            $job = JobPost::find($job_post_id);

            if (empty($job)){
                return back()->with(FlashMsg::item_delete(__('Job not found')));
            }

            if(Auth::guard('sanctum')->check()){
                $request->validate([
                    'budget' => 'required|numeric',
                    'cover_letter'=> 'required',
                ]);

                if($request->budget > $job->budget){
                    return back()->with(FlashMsg::item_delete(__('Your budget must be less than or equal to the original budget')));
                }

                $provider_request_count = JobPostOffer::where('provider_id',$provider_id)
                    ->where('job_post_id',$job_post_id)
                    ->count();

                if($provider_request_count >=1){
                    return back()->with(FlashMsg::item_delete(__('You have already offer for this job.')));
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

                return redirect()->back()->with(FlashMsg::item_new('You have successfully made an offer for this job.'));
               

            }else{
                return back()->with(FlashMsg::item_delete(__('You must login to apply for a job.')));
              
            }
        }
        $job = JobPost::find($job_post_id);

        return view("jobpost::frontend.jobs.job-offer-send",compact('job'));

    }
    public function searchJob(Request $request)
    {
        $all_jobs = JobPost::where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->where('status', 1)->where('is_published', 1)->latest()->paginate(10);
        return $all_jobs->total() >= 1 ? view('jobpost::frontend.jobs.search-result',
            compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function paginateJob(Request $request)
    {
        if($request->ajax()){
            $status = $request->input('status', 'all');

            $query = JobPost::where('status', 1)->where('is_published', 1);
            $provider_id = Auth::user()->id;
            if($status == 1)//applied job
            {
     
                // provider applied jobs
                $job_post_ids = JobPostOffer::where('provider_id', $provider_id)->pluck('job_post_id');
                $job_post_ids = $job_post_ids->toArray();
                $query->whereIn('id', $job_post_ids);
            }
            else if($status == 0)//saved job
            {
                //collect saved job
                $job_post_ids = FavoriteItem::where('user_id', $provider_id)->where('type', 'job')->pluck('item_id');
                $job_post_ids = $job_post_ids->toArray();
                $query->whereIn('id', $job_post_ids);
            }
            else
            {
                // Get all job_post IDs where is_hired = 1 and exclude them
                $job_post_offer_id_pluck = JobPostOffer::where('is_hired', 1)
                ->pluck('job_post_id');
    
                 $query->whereNotIn('id', $job_post_offer_id_pluck);
        
            }
          
            $all_jobs = $query->latest()->paginate(10);
            $current_status=$status;
            return view('jobpost::frontend.jobs.search-result', compact('all_jobs'))->render();
        }
    }


    public function addFavorite(Request $request,$item_id){

        $user_id = Auth::guard('sanctum')->user()->id;
        

        // find user service
        $service = FavoriteItem::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->where('type', 'job')
            ->first();

        if (!empty($service)){
            return back()->with(FlashMsg::item_delete(__('Already added to favorites.')));
        }

        FavoriteItem::create([
            'user_id' => $user_id,
            'item_id' => $item_id,
            'type' => 'job',
        ]);
        return redirect()->back()->with(FlashMsg::item_new('Item successfully added to favorites.'));
    }

    public function favoriteRemove(Request $request,$item_id){

      

        $user_id = Auth::guard('sanctum')->user()->id;

        // Find and remove the favorite item
        $favoriteItem = FavoriteItem::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->where('type', 'job')
            ->first();

        if ($favoriteItem) {
            $favoriteItem->delete();
            return redirect()->back()->with(FlashMsg::item_new('Item removed from favorites.'));
        } else {
            return back()->with(FlashMsg::item_delete(__('Favorite item not found.')));
        }

    }




  


}
