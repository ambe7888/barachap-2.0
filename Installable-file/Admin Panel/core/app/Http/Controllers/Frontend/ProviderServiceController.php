<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\SubCategory;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServiceExclude;
use App\Models\ServiceFaq;
use App\Models\ServiceInclude;
use App\Models\Staff;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;

class ProviderServiceController extends Controller
{

   public function providerAllServices(){
       $provider=Auth::user();
       $provider_id=$provider->id;
       $all_services = Service::where("provider_id",$provider_id)->latest()->paginate(10);
       return view('frontend.frontend.provider.pages.services.provider-services', compact('all_services'));
   }

    public function providerChangeStatus($id){
        $service = Service::select('id','status')->where('id',$id)->first();
        if($service->status==1){
            $status = 0;
        }else{
            $status = 1;
        }
        Service::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }

    public function providerServicePublishedStatus($id)
    {
        // First check if the service exists
        $service = Service::find($id);
        if (!$service) {
            $message = __('Service not found.');
            toastr()->error($message);
            return redirect()->back();
        }
        // service publication status
        $service->is_published = !$service->is_published;
        $service->published_at = now();
        $service->save();

        // Show appropriate message
        if ($service->is_published) {
            $message = __('Service has been successfully published.');
            toastr()->success($message);
        } else {
            $message = __('Service has been successfully unpublished.');
            toastr()->warning($message);
        }

        return redirect()->back();
    }

    public function providerServiceDelete($id){
        try {
            $service = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])->find($id);

            $service->includes()->delete(); // Deletes all related includes
            $service->excludes()->delete(); // Deletes all related excludes
            $service->faqs()->delete(); // Deletes all related FAQs
            $service->addons()->delete(); // Deletes all related addons
            $service->metaData()->delete(); // Deletes the related meta data
            $service->reviews()->delete();
            $service->serviceReports()->delete();
            $service->offer_service()->delete(); // Deletes related offer_service
            $service->delete();

            return redirect()->back()->with(FlashMsg::item_delete(__('Service Deleted Success')));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Service not found.')));
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('An error occurred while deleting the service')));
        }
    }

    // search category
    public function providerSearchService(Request $request)
    {
        $provider=Auth::user();
        $provider_id=$provider->id;
        $all_services = Service::where('provider_id',$provider_id)->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->latest()->paginate(10);
        return $all_services->total() >= 1 ? view('frontend.frontend.provider.pages.services.search-service',
            compact('all_services'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    public function providerPaginate(Request $request)
    {
        if($request->ajax()){
            $provider=Auth::user();
            $provider_id=$provider->id;
            $all_services = Service::where('provider_id',$provider_id)->latest()->paginate(10);
            return view('frontend.frontend.provider.pages.services.search-service', compact('all_services'))->render();
        }
    }

    public function bulkAction(Request $request){
        try {
            $provider=Auth::user();
            $provider_id=$provider->id;
            // Fetch services with the requested IDs and eager load relationships
            $services = Service::with(['includes', 'excludes', 'faqs', 'addons', 'metaData', 'reviews', 'serviceReports','offer_service'])
                ->where('provider_id',$provider_id)
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

    public function providerAddService(Request $request)
    {
        
       if ($request->isMethod('post')) {
   
            // Validate the request data
          $request->validate([
                'category_id' => 'required',
                'title' => 'required|max:191',
                'description' => 'required|min:150',
                'slug' => 'required|unique:services',
                'price' => 'required|numeric',
                // other
                'addons_service_title.*' => 'max:191',
                'faqs_title.*' => 'max:191',
            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                'description.required' => __('The description field is required.'),
                'description.min' => __('The description must be at least 150 characters.'),
                'slug.required' => __('The slug field is required.'),
                'slug.unique' => __('The slug has already been taken.'),
                'price.required' => __('The price field is required.'),
                'price.numeric' => __('The price must be a numeric value.'),

                // other
                'include_service_quantity.*.required' => __('Quantity is required'),
                'include_service_quantity.*.numeric' => __('Quantity must be a number'),
            ]);


            // Retrieve the authenticated provider
            $provider=Auth::user();
            $provider_id=$provider->id;
            // Generate slug from title if not provided
            $slug = $request->filled('slug') ? $request->slug : $request->title;
            // Process video URL if provided
            $video_url = !empty($request->video_url) ? getYoutubeEmbedUrl($request->video_url) : null;

             $service_image = $request->service_image;
             $galleryImagesArray = array_map('trim', explode(',', $request->gallery_images));
             $gallery_images = is_array($galleryImagesArray) ? implode('|', $galleryImagesArray) : $galleryImagesArray;
             $allocate_staff=0;
             if($request->any_staff=='option2')
             {
                 $staffs_id="";
             }
             else
             {
                 $staffs_id = $request->staffs;
             
                 if ($staffs_id) {
                     
                     $allocate_staff=1;
                     $staffs_id = implode(',', $staffs_id);
                 
                 }
             }
             $disable_staff_option=$request->disable_staff;
             if($disable_staff_option=='on')
             {
                $disable_staff=1;
             }
             else
             {
                $disable_staff=0;
             }

            // Create a new Service instance
           $service = Service::create([
                'provider_id' => $provider_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'child_category_id' => $request->child_category_id,
                'title' => $request->title,
                'slug' => Str::slug(purify_html($slug), '-', null),
                'description' => $request->description,
                 'unit' => $request->unit,
                'price' => $request->price,
                'discount_price' => $request->discount_price ?? 0,
                'image' => $service_image,
                'gallery_images' => $gallery_images,
                'video_url' => $video_url,
                'is_featured' => $request->is_featured ?? 0,
                'status' => 1,
                'staffs_id'=>$staffs_id,
                'disable_staff'=>$disable_staff,
                'allocate_staff'=>$allocate_staff,
            ]);


            // Generate meta tags
            $words = explode(' ', $request->input('title'));
            $tags = collect($words)->map(fn($word) => strtolower(trim($word)));
            $tags_name = $tags->implode(', ');

            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];


            // Retrieve the last inserted ID
            $last_service_id = $service->id;

            DB::beginTransaction();
            try {
                $service->metaData()->create($Metas);
                DB::commit();

            }catch (\Throwable $th){
                DB::rollBack();
            }

            $service_update = false;

            // Insert related records
            $this->insertRelatedRecords($request, $last_service_id, $service_update);

            return redirect()->back()->with(FlashMsg::item_new(__('Service Added Success')));
        }
        $provider=Auth::user();
        $provider_id=$provider->id;
        $staffs=Staff::where("provider_id",$provider_id)->where("status",1)->get();


        $categories = Category::where('status', 1)->get();
        $sub_categories = SubCategory::where('status', 1);
        $all_states = State::all_states();
        $all_cities = City::all_cities();
        $all_areas = Area::all_areas();

        return view('frontend.frontend.provider.pages.services.create-service', [
            'categories' => $categories,
            'sub_categories' => $sub_categories,
            'all_states' => $all_states,
            'all_cities' => $all_cities,
            'all_areas' => $all_areas,
            'staffs' => $staffs,
        ]);

    }

    public function providerEditService(Request $request, $id)
    {
        if ($request->isMethod('post')) {

            $request->validate([
                'category_id' => 'required',
                'title' => 'required|max:191',
                'description' => 'required|min:150',
//                'slug' => 'required|unique:services',
                'price' => 'required|numeric',

                // other
                'addons_service_title.*' => 'max:191',
                'faqs_title.*' => 'max:191',
            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                'description.required' => __('The description field is required.'),
                'description.min' => __('The description must be at least 150 characters.'),
                'slug.required' => __('The slug field is required.'),
                'slug.unique' => __('The slug has already been taken.'),
                'price.required' => __('The price field is required.'),
                'price.numeric' => __('The price must be a numeric value.'),

                // other
                'include_service_quantity.*.required' => __('Quantity is required'),
                'include_service_quantity.*.numeric' => __('Quantity must be a number'),
            ]);

            $service = Service::findOrFail($id);
            // country, state, city
            $provider=Auth::user();
            $provider_id=$provider->id;
            $slug = !empty($request->slug) ? $request->slug : Str::slug($service->slug);

            // video url
            $video_url = null;
            if(!empty($request->video_url)){
                $video_url = getYoutubeEmbedUrl($request->video_url);
            }

            $service_image = $request->image;
            $galleryImagesArray = array_map('trim', explode(',', $request->gallery_images));
            $gallery_images = is_array($galleryImagesArray) ? implode('|', $galleryImagesArray) : $galleryImagesArray;
            $allocate_staff=0;
            if($request->any_staff=='option2')
            {
                $staffs_id="";
            }
            else
            {
                $staffs_id = $request->staffs;
            
                if ($staffs_id) {
                    
                    $allocate_staff=1;
                    $staffs_id = implode(',', $staffs_id);
                
                }
            }
            $disable_staff_option=$request->disable_staff;
            if($disable_staff_option=='on')
            {
               $disable_staff=1;
            }
            else
            {
               $disable_staff=0;
            }
           
            // Create a new Service instance
            $service->update([
                'provider_id' => $provider_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'child_category_id' => $request->child_category_id,
                'title' => $request->title,
                'slug' => Str::slug(purify_html($slug), '-', null),
                'description' => $request->description,
                'unit' => $request->unit,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'image' => $service_image,
                'gallery_images' => $gallery_images,
                'video_url' => $video_url,
                'is_featured' => $request->is_featured ?? 0,
                'status' => 1,
                'allocate_staff'=>$allocate_staff,
                'disable_staff'=>$disable_staff,
                'staffs_id'=>$staffs_id,
            ]);

            // Generate meta tags
            $words = explode(' ', $request->input('title'));
            $tags = collect($words)->map(fn($word) => strtolower(trim($word)));
            $tags_name = $tags->implode(', ');

            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];

            // Retrieve the last inserted ID
            $last_service_id = $service->id;
            DB::beginTransaction();
            try {
                $service->metaData()->update($Metas);
                DB::commit();

            }catch (\Throwable $th){
                DB::rollBack();
            }

            $service_update = true;

            // Insert related records
            $this->insertRelatedRecords($request, $last_service_id, $service_update);

            return redirect()->back()->with(FlashMsg::item_new(__('Service Updated Success')));
        }
        $provider=Auth::user();
        $provider_id=$provider->id;
        $staffs=Staff::where("provider_id",$provider_id)->where("status",1)->get();


        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $sub_categories = SubCategory::where('status', 1)->get();
        $child_categories = ChildCategory::where('status', 1)->get();
        $all_countries = State::all_states();
        $all_states = City::all_cities();
        $all_cities = Area::all_areas();

        return view('frontend.frontend.provider.pages.services.edit-service', [
            'service' => $service,
            'categories' => $categories,
            'sub_categories' => $sub_categories,
            'child_categories' => $child_categories,
            'all_countries' => $all_countries,
            'all_states' => $all_states,
            'all_cities' => $all_cities,
            'staffs' => $staffs
        ]);

    }

    private function insertRelatedRecords(Request $request, $serviceId, $service_update)
    {
        // service update
        if ($service_update){
            // Clear existing related records
            ServiceInclude::where('service_id', $serviceId)->delete();
            ServiceExclude::where('service_id', $serviceId)->delete();
            ServiceFaq::where('service_id', $serviceId)->delete();
            ServiceAddon::where('service_id', $serviceId)->delete();
        }
        $flag=false;

        // Prepare and insert included services
        if ($request->filled('include_service_title')) {
            $includedServices = [];
            foreach ($request->include_service_title as $key => $title) {
                if (!empty($title)) {
                    $includedServices[] = [
                        'service_id' => $serviceId,
                        'title' => $title,
                        'description' => $request->include_service_description[$key],
                    ];
                    $flag=true;
                }
               
            }
            if($flag)
            {
                ServiceInclude::insert($includedServices);
            }
            
        }

        // Prepare and insert excluded services
        if ($request->filled('excludes_title')) {
            $excludedServices = [];
            foreach ($request->excludes_title as $key => $title) {
                if (!empty($title)) {
                    $excludedServices[] = [
                        'service_id' => $serviceId,
                        'title' => $title,
                        'description' => $request->excludes_description[$key],
                    ];
                }
            }
            ServiceExclude::insert($excludedServices);
        }

        // Prepare and insert addons services
        if ($request->filled('addons_service_title')) {
            $addonsServices = [];
            foreach ($request->addons_service_title as $key => $title) {
                if (!empty($title)) {
                    $addonsServices[] = [
                        'service_id' => $serviceId,
                        'title' => $title,
                        'price' => $request->addons_service_price[$key],
                        'quantity' => 1,
                        'image' => $request->addons_service_image[$key] ?? null,
                        'description' => $request->addons_service_description[$key] ?? null,
                    ];
                }
            }
            ServiceAddon::insert($addonsServices);
        }

        // Prepare and insert service FAQs
        if ($request->filled('faqs_title')) {
            $serviceFaqs = [];
            foreach ($request->faqs_title as $key => $title) {
                if (!empty($title)) {
                    $serviceFaqs[] = [
                        'service_id' => $serviceId,
                        'title' => $title,
                        'description' => $request->faqs_description[$key],
                    ];
                }
            }
            ServiceFaq::insert($serviceFaqs);
        }
    }


    public function serviceDetails($id,$notificationId=null){
       
        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->find($id);
        $provider=Auth::user();
        $provider_id=$provider->id;
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
                if($provider_id)
                {
                    $staffs=Staff::where('provider_id',$provider_id)->get();
                }
           
            }
            else{
                $staffs=[];
            }
        }
        // if (!$service) {
        //     try {
        //         AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        //     }catch (\Exception $exception){}

        //     abort(404);
        // }
        // AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);

        return view('frontend.frontend.provider.pages.services.service-details', compact('service', 'staffs'));
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
}
