<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderServiceNotification;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserServiceManageController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function allServices(){
        $all_services = Service::providerServices()->latest()->paginate(10);
        return view('backend.pages.services.all_services', compact('all_services'));
    }

    public function serviceDetails($id,$notificationId=null){
       
        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->find($id);
        $admin= Auth::guard('admin')->user();
        $admin_id=$service->admin_id;
        $provider_id=$service->provider_id;
        $service_staffs=$service->staffs_id;
        if($service_staffs)
        {
            $staffs=[];
            if($service->disable_staff==1)
            {
                $service_staffs=explode(',',$service_staffs);
                foreach($service_staffs as $staff)
                {
                $staffs[]=Staff::where('id',$staff)->first();
                }
            }
        }
        else
        {
            if($service->disable_staff==1)
            {
                if($admin_id)
                {
                    $staffs=Staff::where('admin_id',$admin_id)->get();
                }
                else if($provider_id)
                {
                    $staffs=Staff::where('provider_id',$provider_id)->get();
                }
           
            }
            else{
                $staffs=[];
            }
        }
        if (!$service) {
            try {
                AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
            }catch (\Exception $exception){}

            abort(404);
        }
        AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);

        return view('backend.pages.services.service-details', compact('service', 'staffs'));
    }
    public function permanent_delete($service_id)
    {
        try {
            $service = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])->withTrashed()->find($service_id);

            if ($service) {
                $service->includes()->forceDelete(); // Deletes all related includes
                $service->excludes()->forceDelete(); // Deletes all related excludes
                $service->faqs()->forceDelete();    // Deletes all related FAQs
                $service->addons()->forceDelete();  // Deletes all related addons
                $service->metaData()->forceDelete(); // Deletes related meta data
                $service->reviews()->forceDelete(); // Deletes all related reviews
                $service->serviceReports()->forceDelete(); // Deletes all related service reports
                $service->offer_service()->forceDelete(); // Deletes all related offers
                
                $service->forceDelete(); // Deletes the service permanently
            }

        }catch (\Exception $e) {
            return back()->with(FlashMsg::error(__('Services not Deleted Permanently.')));
        }

        return back()->with(FlashMsg::error(__('Services Successfully Deleted Permanently.')));
    }
    function pagination_delete_service(Request $request)
    {
        if($request->ajax()){
            $all_services = Service::providerServices()->onlyTrashed()->latest()->paginate(10);
            return view('backend.pages.providers.trash-service.search-result-for-delete-services', compact('$all_services'))->render();
        }
    }
    public function search_delete_service(Request $request)
    {
        $all_services= Service::providerServices()->withTrashed()->where('deleted_at','!=',null)->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->latest()->paginate(10);
        return $all_services->total() >= 1 ? view('backend.pages.providers.trash-service.search-result-for-delete-services', compact('all_services'))->render() : response()->json(['status'=>__('nothing')]);
    }

    public function userService_restore(Request $request, $id=null)
    {
        if($request->isMethod('post')){
            Service::providerServices()->withTrashed()->find($id)->restore();
            return redirect()->back()->with(FlashMsg::item_new(__('User Successfully Restore')));
        }
        $all_services = Service::providerServices()->onlyTrashed()->latest()->paginate(10);
        return view('backend.pages.providers.trash-service.deleted-services',compact('all_services'));
    }

    public function userServiceAllApproved(){

            // Fetch service to be approved and their associated guest emails
            $services = Service::with('provider')->userServices()->where('status', 0)->get();
            $userEmails = $services->pluck('provider.email')->unique();

            // Update all  in a single query
            $serviceIds = $services->pluck('id')->toArray();

             Service::whereIn('id', $serviceIds)->update([
                    'published_at' => now(),
                    'is_published' => 1,
                    'status' => 1
                ]);

        try {
            // Send email to each batch of guests
            $updated_services = Service::with('provider')
                ->whereIn('id', $serviceIds)
                ->get();

            foreach ($updated_services as $service) {
                // send notification to user
                $statusMessage = $service->is_published == 1 ? __('approved') : __('pending');
                user_notification($service->id, $service->provider_id, 'service', __("Service {$statusMessage}"), 'unread');
                // send to provider fot service active/inactive
                $messageTitle = __('Your service :statusMessage ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);
                $messageBody = __('Your service :statusMessage successfully. ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);

                $provider_notification_data = [
                    "title" => $messageTitle,
                    "detailed_title" => "-",
                    "identify" => $service->id,
                    "user_id" => $service->provider?->id ?? 0,
                    "body" => $messageBody,
                    "description" => "-",
                    "type" => "service",
                    "sound" => "default",
                    "screen" => "-",
                ];

                // Send notifications to each chunk of tokens
                if ($service->provider && !empty($service->provider->firebase_token)) {
                    $this->orderServiceNotification->sendFirebaseNotification(
                        $service->provider?->firebase_token->toArray(),
                        $messageTitle,
                        $messageBody,
                        $provider_notification_data
                    );
                }
            }
        }catch (\Exception $exception){}



            // Split emails into batches of 100
            $emailChunks = $userEmails->chunk(100);
            // Send email to each batch of guests
            foreach ($emailChunks as $chunk) {
                try {
                    $subject = __('Your service approved and published.');
                    $message = __('Your service has been approved and published. Thanks.');

                    // Add "View " button with link to the email message
                    foreach ($services as $service) {
                        $button = '<a href=""><button class="btn btn-info"
                                style="background-color: #17a2b8;
                                border: none;
                                color: white;
                                padding: 10px 20px;
                                text-align: center;
                                text-decoration: none;
                                display: inline-block;
                                font-size: 16px;
                                border-radius: 5px;
                                margin: 4px 2px;
                                cursor: pointer;">' . __('View Service') . '</button></a>';
                        $message .= $button . '<br><br>';
                    }

                    foreach ($chunk as $email) {
                        Mail::to($email)->send(new BasicMail([
                            'subject' => $subject,
                            'message' => $message
                        ]));
                    }

                } catch (\Exception $e) {
                    // Handle exceptions if needed
                }
            }

        return redirect()->back()->with(FlashMsg::item_new(__('User All Service Approved Success')));
    }

    public function changeStatus($id){
        $service = Service::with('provider')->where('id',$id)->first();
        if($service->status==1){
            $service->update([
                'status' => 0,
            ]);
        }else{
            $service->update([
                'published_at' => now(),
                'is_published' => 1,
                'status' => 1,
            ]);
        }

        // if service status approve/Pending email send
        // sent email to user
        $service = Service::with('provider')->where('id',$id)->first();

        try {
                // send notification to user
                $statusMessage = $service->status == 1 ? __('approved') : __('pending');
                user_notification($service->id, $service->provider_id, 'service', __("Service {$statusMessage}"), 'unread');
                // send to provider fot service active/inactive
                $messageTitle = __('Your service :statusMessage ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);
                $messageBody = __('Your service :statusMessage successfully. ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);

                $provider_notification_data = [
                    "title" => $messageTitle,
                    "detailed_title" => "-",
                    "identify" => $service->id,
                    "user_id" => $service->provider?->id ?? 0,
                    "body" => $messageBody,
                    "description" => "-",
                    "type" => "service",
                    "sound" => "default",
                    "screen" => "-",
                ];

                // Pass the token as an array
                $this->orderServiceNotification->sendFirebaseNotification([$service->provider?->firebase_token ?? ''], $messageTitle, $messageBody, $provider_notification_data);

            $subject = get_static_option('service_approve_subject') ?? __('Your service has been approved.');
            $message = get_static_option('service_approve_message') ?? __('Your service has been approved. Thanks.');
            $message = str_replace(["@service_id"], [$service->id], $message);
            Mail::to($service->user?->email)->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));
        } catch (\Exception $e) {}


        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }


    public function makeFeatured($id){
        $service = Service::with('provider')
            ->where('id',$id)
            ->first();

        if (!$service) {
            return redirect()->back()->with(FlashMsg::item_new(__('Service not found.')));
        }

        if ($service->is_featured == 0) {
            $service->update([
                'is_featured' => 1,
            ]);
        }else{
            $service->update([
                'is_featured' => 0,
            ]);
        }


        if ($service->is_featured == 1){
            $message_title = FlashMsg::item_new(__('Service added to Featured Success'));

            // sent email to provider
            try {
                $subject = __('Your Service added to Featured List.');
                $message = __('Your service (ID: @service_id) has been added to the Featured List. Thanks.');
                $message = str_replace('@service_id', $service->id, $message);
                Mail::to($service->provider?->email)->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

        }else{
            $message_title = FlashMsg::item_delete(__('Service remove to Featured Success'));

            // sent email to provider
            try {
                $subject = __('Your Service removed to Featured List.');
                $message = __('Your service (ID: @service_id) has been removed to the Featured List. Thanks.');
                $message = str_replace('@service_id', $service->id, $message);
                Mail::to($service->provider?->email)->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }

        return redirect()->back()->with($message_title);
    }

    public function servicePublishedStatus($id)
    {
        // First check if the service exists
        $service = Service::with('provider')->find($id);
        if (!$service) {
            $message = __('service not found.');
            toastr()->error($message);
            return redirect()->back();
        }

        // publication status
        $service->is_published = !$service->is_published;
        $service->published_at = now();
        $service->save();

        // Show appropriate message
        if ($service->is_published) {
            // is published
            $message = __('Service has been successfully published.');
            toastr()->success($message);
        } else {
            // is unpublished
            $message = __('Service has been successfully unpublished.');
            toastr()->warning($message);
        }


        $service = Service::with('provider')->find($id);
        try {
            // send notification to user
            $statusMessage = $service->is_published == 1 ? __('published') : __('unpublished');
            user_notification($service->id, $service->provider_id, 'service', __("Service {$statusMessage}"), 'unread');
            // send to provider fot service active/inactive
            $messageTitle = __('Your service :statusMessage ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);
            $messageBody = __('Your service :statusMessage successfully. ID#:serviceId', ['statusMessage' => $statusMessage, 'serviceId' => $service->id]);

            $provider_notification_data = [
                "title" => $messageTitle,
                "detailed_title" => "-",
                "identify" => $service->id,
                "user_id" => $service->provider?->id ?? 0,
                "body" => $messageBody,
                "description" => "-",
                "type" => "service",
                "sound" => "default",
                "screen" => "-",
            ];
            // Pass the token as an array
            $this->orderServiceNotification->sendFirebaseNotification([$service->provider?->firebase_token ?? ''], $messageTitle, $messageBody, $provider_notification_data);

        }catch (\Exception $exception){}

        // mail
        if ($service->is_published){
            // sent email to user for service published
            try {
                $subject = get_static_option('service_publish_subject') ?? __('Your service has been published.');
                $message = get_static_option('service_publish_message') ?? __('Your service has been published. Thanks.');
                $message = str_replace(["@service_id"], [$service->id], $message);
                Mail::to($service->user?->email)->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }else{
            // sent email to user for service Unpublished
            try {
                $subject = get_static_option('service_unpublished_subject') ?? __('Your service has been unpublished.');
                $message = get_static_option('service_unpublished_message') ?? __('Your service has been unpublished. Thanks.');
                $message = str_replace(["@service_id"], [$service->id], $message);
                Mail::to($service->user?->email)->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }


        return redirect()->back();
    }

    public function serviceDelete($id){
        try {
            $service = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])->find($id);

            $service->includes()->delete(); // Deletes all related includes
            $service->excludes()->delete(); // Deletes all related excludes
            $service->faqs()->delete(); // Deletes all related FAQs
            $service->addons()->delete(); // Deletes all related addons
            $service->metaData()->delete(); // Deletes the related meta data
            $service->reviews()->delete();
            $service->serviceReports()->delete();
            $service->offer_service()->delete(); // Deletes all related offer services
            $service->delete();

            return redirect()->back()->with(FlashMsg::item_delete(__('Service Deleted Success')));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Service not found.')));
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('An error occurred while deleting the service')));
        }
    }

    // search category
    public function searchService(Request $request)
    {
        $all_services = Service::userServices()->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->latest()->paginate(10);
        return $all_services->total() >= 1 ? view('backend.pages.services.search-service',
            compact('all_services'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
   public function paginate(Request $request)
    {
        if($request->ajax()){
            $all_services = Service::userServices()->latest()->paginate(10);
            return view('backend.pages.services.search-service', compact('all_services'))->render();
        }
    }

    public function bulkAction(Request $request){
        try {
            // Fetch services with the requested IDs and eager load relationships
            $services = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])
                ->userServices()
                ->whereIn('id',$request->ids)
                ->get();

            // Loop through each service to delete related records
            foreach ($services as $service) {
                // Delete related models
                $service->includes()->delete(); // Deletes all related includes
                $service->excludes()->delete(); // Deletes all related excludes
                $service->faqs()->delete(); // Deletes all related FAQs
                $service->addons()->delete(); // Deletes all related addons
                $service->metaData()->delete(); // Deletes the related meta data
                $service->reviews()->delete(); // Deletes all related reviews
                $service->serviceReports()->delete(); // Deletes all related reports
                $service->offer_service()->delete(); // Deletes related offer_service

                // Finally, delete the service itself
                $service->delete();
            }
        }catch (\Exception $e){}

        return response()->json(['status' => 'ok']);
    }

}
