<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reviews\ReviewResource;
use App\Http\Resources\Reviews\ServiceReviewResource;
use App\Http\Resources\Services\RelevantServiceListResource;
use App\Http\Resources\Services\ScheduleResource;
use App\Http\Resources\Services\ServiceDetailsResource;
use App\Http\Resources\Services\ServiceSummaryResource;
use App\Http\Resources\Services\ServicsResource;
use App\Http\Resources\StaffResource;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;

class ServiceController extends Controller
{

    public function staffList(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $provider_id = $request->input('provider_id');

        $query = Staff::query();
        if (!empty($admin_id)) {
            $query->where('admin_id', $admin_id);
        } elseif (!empty($provider_id)) {
            $query->where('provider_id', $provider_id);
        } else {
            return response()->json([
                'status' => __('Provider ID or Admin ID must be provided.'),
            ], 400);
        }

        $all_staffs = $query->get();
        if ($all_staffs->isNotEmpty()) {
            return response()->json([
                'all_staffs' => StaffResource::collection($all_staffs),
            ]);
        }
        return response()->json([
            'status' => __('No schedules found.'),
        ], 404);
    }


    public function scheduleByDay(Request $request)
    {

        $day = $request->input('day');
        //make this always first letter capital
        $day = ucfirst(strtolower($day));
        $provider_id = $request->input('provider_id');
        $admin = $request->input('admin');

        if (isset($admin)) {
            $schedules = Schedule::whereNotNull('admin_id')
                ->where('day', $day)
                ->get();
        }else{
            $schedules = Schedule::where('provider_id', $provider_id)
                ->where('day', $day)
                ->get();
        }

        if ($schedules->isNotEmpty()) {
            return response()->json([
                'schedules' => ScheduleResource::collection($schedules),
            ]);
        }

        return response()->json([
            'status' => __('No schedule found'),
        ], 404);
    }


    public function allServices(Request $request)
    {

        $title = $request->input('title');
        $sort = $request->input('sort', 'desc');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'created_at');
        $cat_id = $request->input('cat_id');


        $query = Service::with( 'reviews', 'includes', 'excludes', 'faqs', 'addons', 'provider', 'admin','offer_service')
            ->where('status', 1)
            ->where('is_published', 1);

        // dd($query);
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Filter by category
        if ($cat_id) {
            $query->where('category_id', $cat_id);
        }

        // rating by filter
        if (!empty(request()->get("rating"))) {
            $rating = (int) request()->get("rating");
            $query->whereHas("reviews", function ($q) use ($rating) {
                $q->groupBy("reviews.id")
                    ->havingRaw("AVG(reviews.rating) >= ?", [$rating])
                    ->havingRaw("AVG(reviews.rating) < ?", [$rating + 1]);
            });
        }


        // Sort by specified column and order
        if ($sortBy === 'created_at') {
            $query->orderBy('created_at', $sort);
        }

        $all_services = $query->paginate(10);

        if ($all_services->isNotEmpty()) {
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
        ]);
    }


    // service details
    public function serviceDetails($id=null){
        // Validate the provided ID
        if (is_null($id) || !is_numeric($id)) {
            return response()->json([
                'message' => __('Invalid service ID provided'),
            ]);
        }

        // service details
        $query = Service::with([
            'includes',
            'excludes',
            'faqs',
            'addons',
            'provider',
            'admin',
            'category',
            'sub_category',
            'child_category',
            'provider.providerStaff',
            'admin.adminStaff',
            'reviews.reviewer',
            'offer_service'
        ]);

       $service_details = $query->where('id', $id)
            ->where('status', 1)
            ->where('is_published', 1)
            ->first();

        // Validate the provided ID
        if (is_null($service_details)) {
            return response()->json([
                'message' => __('Service not found'),
            ]);
        }

        $provider_all_reviews = Review::with('reviewer')->where('user_id', $service_details->provider_id)
            ->where("user_type", 0)
            ->latest()
            ->paginate(10);


        $provider_rating = Review::where('user_id', $service_details->provider_id)->where("user_type", 0)->avg('rating');
        $provider_rating_percentage_value = round($provider_rating * 20);


        $admin_all_reviews = Review::with('reviewer')->where('user_id', $service_details->admin_id)
            ->where("user_type", 2)
            ->latest()
            ->paginate(10);


        $admin_rating = Review::where('user_id', $service_details->admin_id)->where("user_type", 2)->avg('rating');
        $admin_rating_percentage_value = round($admin_rating * 20);

        // Fetch relevant services based on provider and category
        $relevant_services = Service::with([
            'includes',
            'excludes',
            'faqs',
            'addons',
            'provider',
            'admin',
            'category',
            'sub_category',
            'child_category',
            'provider.providerStaff',
            'admin.adminStaff',
            'reviews.reviewer',
            'offer_service'
        ])
        ->where('status', 1)
        ->where('is_published', 1)
        ->where(function ($query) use ($service_details) {
            $query->Where('category_id', $service_details->category_id)
                  ->orWhere('provider_id', $service_details->provider_id)
                  ->orWhere('admin_id', $service_details->admin_id);
        })
         ->where('id', '!=', $id)
        ->limit(10)
        ->get();


       $all_reviews=[];
       $rating=0;
       if ($admin_all_reviews->count() > 0) {
            $rating=$admin_rating_percentage_value;
            $all_reviews = [ 'reviews' => $admin_all_reviews ? ReviewResource::collection($admin_all_reviews) : null,
            'pagination' => [
                'total' => $admin_all_reviews->total(),
                'count' => $admin_all_reviews->count(),
                'per_page' => $admin_all_reviews->perPage(),
                'current_page' => $admin_all_reviews->currentPage(),
                'last_page' => $admin_all_reviews->lastPage(),
                'next_page_url' => $admin_all_reviews->nextPageUrl(),
                'prev_page_url' => $admin_all_reviews->previousPageUrl(),
            ]];
        } elseif ($provider_all_reviews->count() > 0) {
            $rating=$provider_rating_percentage_value;
            $all_reviews = ['reviews' => $provider_all_reviews ? ReviewResource::collection($provider_all_reviews) : null,
                    'pagination' => [
                        'total' => $provider_all_reviews->total(),
                        'count' => $provider_all_reviews->count(),
                        'per_page' => $provider_all_reviews->perPage(),
                        'current_page' => $provider_all_reviews->currentPage(),
                        'last_page' => $provider_all_reviews->lastPage(),
                        'next_page_url' => $provider_all_reviews->nextPageUrl(),
                        'prev_page_url' => $provider_all_reviews->previousPageUrl(),
                    ]];
        }

        if ($service_details) {
                return response()->json([
                    'service_details' => new ServiceDetailsResource($service_details),
                    'relevant_service_lists' => $relevant_services ? ServiceSummaryResource::collection($relevant_services) : null,
                    'rating' => $rating,
                    'all_reviews' => $all_reviews,

                ]);


        }

        // Return a not found response if no service was found
        return response()->json([
            'message' => __('Service Not Available'),
        ]);

    }


    public function featuredServices(Request $request)
    {
        $query = Service::with( 'reviews', 'includes', 'excludes', 'faqs', 'addons', 'provider')
            ->where('status',1)
            ->where('is_published',1)
            ->where('is_featured', 1);

        $all_services = $query->inRandomOrder()->paginate(10);
        if ($all_services->isNotEmpty()) {
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
        ]);
    }

    public function popularServices(Request $request)
    {
        $popularServices = Service::with('reviews', 'includes', 'excludes', 'faqs', 'addons', 'provider')
            ->select('services.*')
            ->leftJoin('sub_orders', 'services.id', '=', 'sub_orders.service_id')
            ->where('services.status', 1)
            ->where('services.is_published', 1)
            ->groupBy('services.id')
            ->orderByRaw('COUNT(sub_orders.id) DESC')
            ->paginate(10);

        if ($popularServices->isNotEmpty()) {
            return response()->json([
                'all_services' => ServiceSummaryResource::collection($popularServices->items()),
                'pagination' => [
                    'total' => $popularServices->total(),
                    'count' => $popularServices->count(),
                    'per_page' => $popularServices->perPage(),
                    'current_page' => $popularServices->currentPage(),
                    'last_page' => $popularServices->lastPage(),
                    'next_page_url' => $popularServices->nextPageUrl(),
                    'prev_page_url' => $popularServices->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Service Not Available'),
        ]);
    }


}
