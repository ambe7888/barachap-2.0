<?php

namespace Modules\JobPost\app\Http\Controllers\Backend;

use App\Actions\Services\ImageModifier;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderServiceNotification;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;

class AdminJobController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function jobs()
    {
        $all_jobs = JobPost::latest()->paginate(10);
        return view('jobpost::backend.jobs.all-jobs',compact('all_jobs'));
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

        try {
            AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        }catch (\Exception $exception){}

        return view('jobpost::backend.jobs.job-details',compact('job'));
    }

    public function all_offers($id)
    {
        $all_offers = JobPostOffer::with('job', 'provider')
            ->where('job_post_id',$id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('jobpost::backend.offers.all-offers',compact('all_offers'));
    }

    public function permanent_delete($job_id)
    {
        try {
            $job = JobPost::withTrashed()->find($job_id);
            if (!$job) {
                return redirect()->back()->with(FlashMsg::item_new('Job not found'));
            }
            JobPostOffer::where('job_post_id', $job_id)->forceDelete();
            $job->forceDelete();

        }catch (\Exception $e) {
            return back()->with(FlashMsg::error(__('Job not Deleted Permanently.')));
        }

        return back()->with(FlashMsg::error(__('Job Successfully Deleted Permanently.')));
    }
    function pagination_delete_job(Request $request)
    {
        if($request->ajax()){
            $all_jobs = JobPost::onlyTrashed()->latest()->paginate(10);
            return view('jobpost::backend.jobs.search-result-for-trash-jobs', compact('$all_jobs'))->render();
        }
    }
    public function search_delete_job(Request $request)
    {
        $all_jobs= JobPost::withTrashed()->where('deleted_at','!=',null)->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->latest()->paginate(10);
        return $all_jobs->total() >= 1 ? view('jobpost::backend.jobs.search-result-for-trash-jobs', compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    public function userJob_restore(Request $request, $id=null)
    {
        if($request->isMethod('post')){
            JobPost::withTrashed()->find($id)->restore();
            return redirect()->back()->with(FlashMsg::item_new(__('Job Successfully Restore')));
        }
        $all_jobs = jobPost::onlyTrashed()->latest()->paginate(10);
        return view('jobpost::backend.jobs.deleted-jobs',compact('all_jobs'));
    }

    public function change_status($id)
    {
        $job = JobPost::with('job_creator', 'client')->find($id);
        $job->status == 1 ? $status = 0 : $status = 1;

        $job->update([
            'status'=>$status,
        ]);

        //Email to user
        try {
            $job = JobPost::with('job_creator', 'client')->find($id);
            // send notification to user
            $statusMessage = $job->status == 1 ? __('activated') : __('inactive');
            user_notification($job->id, $job->client_id, 'job', __("Job {$statusMessage}"), 'unread');

            // send to client fot job post active
            $messageTitle = __('Job :statusMessage ID#:jobId', ['statusMessage' => $statusMessage, 'jobId' => $job->id]);
            $messageBody = __('Your Job :statusMessage successfully. Job ID#:jobId', ['statusMessage' => $statusMessage, 'jobId' => $job->id]);

            $client_notification_data = [
                "title" => $messageTitle,
                "detailed_title" => "-",
                "identify" => $job->id,
                "user_id" => $job->client?->id ?? 0,
                "body" => $messageBody,
                "description" => "-",
                "type" => "job",
                "sound" => "default",
                "screen" => "-",
            ];

            // Pass the token as an array
            $this->orderServiceNotification->sendFirebaseNotification([$job->client?->firebase_token], $messageTitle, $messageBody, $client_notification_data);

            $message = __("Job Post {$statusMessage}");
            $message = str_replace(
                ["@name", "@job_id"],
                [$job->job_creator?->fullname, $job->id],
                $message
            );
            Mail::to($job->job_creator?->email)->send(new BasicMail([
                'subject' => __("Job Post {$statusMessage}"),
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        return redirect()->back()->with(FlashMsg::item_new('Status Changed Success'));
    }

    public function delete($id){
        DB::beginTransaction();
        try {
            $job = JobPost::find($id);
            if (!$job) {
                DB::rollBack();
                return redirect()->back()->with(FlashMsg::item_new('Job not found'));
            }
            JobPostOffer::where('job_post_id', $id)->delete();
            $job->delete();
            DB::commit();
            return redirect()->back()->with(FlashMsg::item_new('Job deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(FlashMsg::item_new('An error occurred while deleting the job.'));
        }
    }

    public function all_request($id)
    {
        $all_request = JobPostOffer::with('job')->where('job_post_id',$id)->orderByDesc('id')->get();
        return view('jobpost::backend.all-request',compact('all_request'));
    }

    public function jobPublishedStatus($id)
    {
        // First check if the job exists
        $job = JobPost::with('client')->find($id);
        if (!$job) {
            $message = __('Service not found.');
            toastr()->error($message);
            return redirect()->back();
        }
        // job publication status
        $job->is_published = !$job->is_published;
        $job->published_at = now();
        $job->save();

        try {
            $job = JobPost::with('client')->find($id);
            // send notification to user
            $statusMessage = $job->is_published == 1 ? __('published') : __('unpublished');
            user_notification($job->id, $job->client_id, 'job', __("Job {$statusMessage}"), 'unread');

            // send to client fot job post active
            $messageTitle = __('Job :statusMessage ID#:jobId', ['statusMessage' => $statusMessage, 'jobId' => $job->id]);
            $messageBody = __('Your Job :statusMessage successfully. Job ID#:jobId', ['statusMessage' => $statusMessage, 'jobId' => $job->id]);

            $client_notification_data = [
                "title" => $messageTitle,
                "detailed_title" => "-",
                "identify" => $job->id,
                "user_id" => $job->client?->id ?? 0,
                "body" => $messageBody,
                "description" => "-",
                "type" => "job",
                "sound" => "default",
                "screen" => "-",
            ];

            // Pass the token as an array
            $this->orderServiceNotification->sendFirebaseNotification([$job->client?->firebase_token], $messageTitle, $messageBody, $client_notification_data);
        }catch (\Exception $exception){}


        // Show appropriate message
        if ($job->is_published) {
            $message = __('Job has been successfully published.');
            toastr()->success($message);
        } else {
            $message = __('Job has been successfully unpublished.');
            toastr()->warning($message);
        }

        return redirect()->back();
    }

    public function searchJob(Request $request)
    {
        $all_jobs = JobPost::where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->latest()->paginate(10);
        return $all_jobs->total() >= 1 ? view('jobpost::backend.jobs.search-result',
            compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function paginateJob(Request $request)
    {
        if($request->ajax()){
            $all_jobs = JobPost::latest()->paginate(10);
            return view('jobpost::backend.jobs.search-result', compact('all_jobs'))->render();
        }
    }

    public function bulkAction(Request $request){
        JobPost::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }


    public function jobSettings()
    {
        return view('jobpost::backend.job-settings');
    }
    public function jobCreateSettingsUpdate(Request $request)
    {
        update_static_option('job_create_settings',$request->job_create_settings);
        update_static_option('job_overview_title',$request->job_overview_title);
        update_static_option('job_starting_at_price_title',$request->job_starting_at_price_title);
        return redirect()->back()->with(FlashMsg::item_new('Update Success'));

    }

}
