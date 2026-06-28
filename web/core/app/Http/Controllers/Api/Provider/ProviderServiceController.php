<?php

namespace App\Http\Controllers\Api\Provider;

use App\Actions\Media\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\Services\ServiceSummaryResource;
use App\Http\Resources\Services\ServicsResource;
use App\Http\Services\AllServiceManage;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\SubCategory;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServiceExclude;
use App\Models\ServiceFaq;
use App\Models\ServiceInclude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProviderServiceController extends Controller
{

    protected $allServiceManage;

    public function __construct(AllServiceManage $allServiceManage)
    {
        $this->middleware('auth:sanctum');
        $this->allServiceManage = $allServiceManage;
    }

    public function addService(StoreServiceRequest $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $request->validated();
        $service = $this->allServiceManage->createService($request);
        return response()->json([
            'service' => new ServicsResource($service),
            'message' => __('Service Successfully Added'),
        ]);
    }

    public function editService(StoreServiceRequest $request, $serviceId)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $request->validated();

        // Fetch the existing service
        $service = Service::find($serviceId);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $service = $this->allServiceManage->updateService($request, $serviceId);

        return response()->json([
            'service' => new ServicsResource($service),
            'message' => __('Service Successfully Updated'),
        ]);
    }

    public function deleteService($id){
        try {
            $service = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])->find($id);

            $service->includes()->delete(); // Deletes all related includes
            $service->excludes()->delete(); // Deletes all related excludes
            $service->faqs()->delete(); // Deletes all related FAQs
            $service->addons()->delete(); // Deletes all related addons
            $service->metaData()->delete(); // Deletes the related meta data
            $service->reviews()->delete();
            $service->serviceReports()->delete();
            $service->offer_service()->delete(); 
            $service->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('Service Successfully Deleted'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Service not found'),
            ],404);
        } catch (\Exception $e) {
           
            return response()->json([
                'status' => 'failed',
                'message' => __('An error occurred while deleting the service'),
            ],500);
        }
    }




    public function providerAllServices(Request $request)
    {

        $title = $request->input('title');
        $status = $request->input('status');
        $is_published = $request->input('is_published');

        $query = Service::with('includes', 'excludes', 'faqs', 'addons')
            ->where('provider_id', Auth::guard('sanctum')->user()->id);


        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        if ($status !== null) {
            $query->where('status', intval($status));
        }

        if ($is_published !== null) {
            $query->where('is_published', intval($is_published));
        }

        $all_services = $query->latest()->paginate(10);

        if ($all_services->isNotEmpty()) {
            // dd(ServiceSummaryResource::collection($all_services->items()));
            return response()->json([
                'all_services' => ServiceSummaryResource::collection($all_services->items()),
                'pagination' => [
                    'total' => $all_services->total(),
                    'count' => $all_services->count(),
                    'per_page' => $all_services->perPage(),
                    'current_page' => $all_services->currentPage(),
                    'last_page' => $all_services->lastPage(),
                    'next_page_url' => $all_services->nextPageUrl(),
                    'prev_page_url' => $all_services->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Service Not Available'),
        ], 200);

    }


    public function subCategoryByCategory($category)
    {
        if($category){
            $sub_category = SubCategory::where('category_id',$category)->get();
            return response()->success([
                'sub_category' => $sub_category,
            ]);
        }else{
            return response()->error([
                'message' => __('Category not found'),
            ]);
        }
    }

    public function childCategoryBySubcategory($sub_category)
    {
        if($sub_category){
            $child_category = ChildCategory::where('sub_category_id',$sub_category)->get();
            return response()->success([
                'child_category' => $child_category,
            ]);
        }else{
            return response()->error([
                'message' => __('Subcategory not found'),
            ]);
        }
    }

    public function ServiceOnOff($id)
    {
        $is_service_on = Service::select('is_service_on')->where('id', $id)->first();
        if ($is_service_on->is_service_on == 1) {
            $is_service_on = 0;
            Service::where('id', $id)->update(['is_service_on' => $is_service_on]);
            return response()->success(['msg'=> __('Service Off Successfully.')]);
        } else {
            $is_service_on = 1;
            Service::where('id', $id)->update(['is_service_on' => $is_service_on]);
            return response()->success(['msg'=> __('Service On Successfully.')]);
        }
    }


    public function serviceDetails($id=null){
        // Validate the provided ID
        if (is_null($id) || !is_numeric($id)) {
            return response()->json([
                'message' => __('Invalid service ID provided'),
            ], 400);
        }

        // Fetch the service details
        $service_details = Service::with('includes', 'excludes', 'faqs', 'addons', 'reviews')
            ->where('id', $id)
            ->where('provider_id', Auth::guard('sanctum')->user()->id)
            ->first();

        if (is_null($service_details)) {
            return response()->json([
                'message' => __('Service not found'),
            ], 400);
        }

        // Check if service was found
        if ($service_details) {
            return response()->json([
                'service_details' => new ServicsResource($service_details),
            ]);
        }

        // Return a not found response if no service was found
        return response()->json([
            'message' => __('Service Not Available'),
        ], 404);

    }


    public function servicePublishedStatus($id)
    {
        // First check if the service exists
        $service = Service::where('id', $id)->where('provider_id', Auth::guard('sanctum')->user()->id)->first();

        if (!$service) {
            $message = __('service not found.');
            return response()->json([
                'message' => $message,
            ]);
        }

        // publication status
        $service->is_published = !$service->is_published;
        $service->published_at = now();
        $service->save();

        // Show appropriate message
        if ($service->is_published) {
            // is published
            $message = __('Service has been successfully published.');
            return response()->json([
                'message' => $message,
            ]);
        } else {
            // is unpublished
            $message = __('Service has been successfully unpublished.');
            return response()->json([
                'message' => $message,
            ]);
        }
    }


}
