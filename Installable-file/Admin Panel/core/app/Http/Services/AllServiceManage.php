<?php

namespace App\Http\Services;


use App\Actions\Media\MediaHelper;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServiceExclude;
use App\Models\ServiceFaq;
use App\Models\ServiceInclude;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AllServiceManage
{
    public function createService(Request $request): Service
    {

        $provider = Auth::guard('sanctum')->user();
        // Generate slug from title if not provided
        $slug = $request->filled('slug') ? $request->slug : $request->title;
        $generated_slug = Str::slug(purify_html($slug));
        $originalSlug = $generated_slug;
        $counter = 1;
        if (!empty($generated_slug)){
            while (Service::where('slug', $generated_slug)->exists()) {
                $generated_slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }


        // Process video URL if provided
        $video_url = !empty($request->video_url) ? getYoutubeEmbedUrl($request->video_url) : null;

        if($request->file('image')){
            MediaHelper::insert_media_image($request,'web','image');
            $image_id = DB::getPdo()->lastInsertId();
        }

        $galleryImages = [];
        // Check if 'gallery_images' is provided
        if ($request->hasFile('gallery_images')) {
            $files = $request->file('gallery_images');
            // Ensure $files is an array
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                // Insert the image into the media gallery and retrieve its ID
                $imageId = MediaHelper::insert_media_gallery_images($file, 'web');
                if ($imageId) {
                    $galleryImages[] = $imageId;
                }
            }
        }
        // Convert gallery image IDs array to a pipe-separated string if needed
        $galleryImagesString = implode('|', $galleryImages);

        // check service approve and pending status
        $service_status = !empty(get_static_option('service_create_status_settings')) ? 1 : 0;
        $allocate_staff=0;
        $staffs_id = $request->staffs_id ?? null;
        if ($staffs_id) {
            $allocate_staff=1;
            $staffs_id = str_replace(['[', ']'], '', $staffs_id);
            $staffs_ids = explode(',', $staffs_id);
            $staffs_id="";
            foreach ($staffs_ids as $staff)
            {
                $providerStaff=Staff::where('id', $staff)->where('provider_id',$provider->id)->first();
                if($providerStaff){
                    $staffs_id.=$staff.",";
                }    
            }
        }
             
        // Create a new Service instance
        $service = Service::create([
            'provider_id' => $provider->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id,
            'title' => $request->title,
            'slug' => Str::slug(purify_html($slug), '-', null),
            'description' => $request->description,
            'unit' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'image' => $image_id ?? null,
            'gallery_images' => $galleryImagesString ?? null,
            'video_url' => $video_url,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $service_status,
            'staffs_id' => $staffs_id,
            'disable_staff'=> $request->disable_staff ?? 0,
            'allocate_staff' => $allocate_staff
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
        // create meta data
        $service->metaData()->create($Metas);
        $service_update = false;
        // Insert related records
        $this->insertRelatedRecords($request, $service->id, $service_update);
        $message=__('A new service(#:service_id) has been created.', ['service_id' => $last_service_id]);
        //create service notification to admin
        admin_notification($last_service_id, $provider->id, 'service', $message, 'unread');

        // get service
        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->find($last_service_id);

        if (get_static_option('service_create_status_settings') == 'pending') {
            try {
                $message = get_static_option('service_approve_message');
                $message = str_replace(["@service_id"], [$last_service_id], $message);
                $subject = get_static_option('service_approve_subject') ?? __('New Service Approve Request');

                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));
            } catch (\Exception $e) {
            }
        }

        return $service;

    }

    public function updateService(Request $request, $serviceId): Service
    {
        $provider = Auth::guard('sanctum')->user();
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        // Generate slug from title if not provided
        $slug = $request->filled('slug') ? $request->slug : $request->title;

        // Process video URL if provided
        $video_url = !empty($request->video_url) ? getYoutubeEmbedUrl($request->video_url) : null;

        if($request->file('image')){
            MediaHelper::insert_media_image($request,'web','image');
            $image_id = DB::getPdo()->lastInsertId();
        }

        $galleryImages = [];
        // Check if 'gallery_images' is provided
        if ($request->hasFile('gallery_images')) {
            $files = $request->file('gallery_images');
            // Ensure $files is an array
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                // Insert the image into the media gallery and retrieve its ID
                $imageId = MediaHelper::insert_media_gallery_images($file, 'web');
                if ($imageId) {
                    $galleryImages[] = $imageId;
                }
            }
        }
        // Convert gallery image IDs array to a pipe-separated string if needed
        $galleryImagesString = implode('|', $galleryImages);

        $old_img = $service->image;
        $old_gallery_images = $service->image;

        // check service approve and pending status
        $service_status = !empty(get_static_option('service_create_status_settings')) ? 1 : 0;
        $allocate_staff=0;
        $staffs_id = $request->staffs_id ?? null;
        if ($staffs_id) {
            $allocate_staff=1;
            $staffs_id = str_replace(['[', ']'], '', $staffs_id);
            $staffs_ids = explode(',', $staffs_id);
            $staffs_id="";
            foreach ($staffs_ids as $staff)
            {
                $providerStaff=Staff::where('id', $staff)->where('provider_id',$provider->id)->first();
                if($providerStaff){
                    $staffs_id.=$staff.",";
                }    
            }
        }
        
        // Create a new Service instance
        $service->update([
            'provider_id' => $provider->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id,
            'title' => $request->title,
            'slug' => Str::slug(purify_html($slug), '-', null),
            'description' => $request->description,
            'unit' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'image' => $image_id ?? $old_img,
            'gallery_images' => $galleryImagesString ?? $old_gallery_images,
            'video_url' => $video_url,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $service_status,
            'staffs_id' => $staffs_id,
            'disable_staff'=> $request->disable_staff ?? 0,
            'allocate_staff' => $allocate_staff
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
        // create meta data
        $service->metaData()->create($Metas);
        // Insert related records
        $service_update = true;
        $this->insertRelatedRecords($request, $serviceId, $service_update);
        // get service
        $service = Service::with('includes', 'excludes', 'faqs', 'addons')->find($last_service_id);
        return $service;
    }

    private function insertRelatedRecords(Request $request, $serviceId, $service_update)
    {
        $last_service_id = $serviceId;
        $data = $request->all();
        $all_include_service = [];
        $all_exclude_service = [];
        $all_faq_service = [];
        $all_addons_service = [];

        $includes_service_count = 0;
        $excludes_service_count = 0;
        $faq_service_count = 0;
        $addons_service_count = 0;

        if ($service_update){
            // Clear existing related records
            ServiceInclude::where('service_id', $serviceId)->delete();
            ServiceExclude::where('service_id', $serviceId)->delete();
            ServiceFaq::where('service_id', $serviceId)->delete();
            ServiceAddon::where('service_id', $serviceId)->delete();
        }


        // all_include_service
        if (isset($data['all_include_service'])) {
            $new_include_services = json_decode($data['all_include_service'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Invalid JSON format.',
                    'error' => json_last_error_msg(),
                ], 400);
            }

            // Process each item in the array
            foreach ($new_include_services as $serviceData) {
                if (isset($serviceData['include_service_title'])) {
                    $all_include_service[] = [
                        'service_id' => $last_service_id,
                        'title' => $serviceData['include_service_title'],
                        'description' => $serviceData['include_service_description'],
                    ];
                    $includes_service_count++;
                }
            }

            // Insert included services
            if ($includes_service_count > 0) {
                ServiceInclude::insert($all_include_service);
            }
        }

        // all_excludes_service
        if (isset($data['all_excludes_service'])) {
            $new_excludes_services = json_decode($data['all_excludes_service'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Invalid JSON format.',
                    'error' => json_last_error_msg(),
                ], 400);
            }

            // Process each item in the array
            foreach ($new_excludes_services as $serviceData) {
                if (isset($serviceData['exclude_service_title'])) {
                    $all_exclude_service[] = [
                        'service_id' => $last_service_id,
                        'title' => $serviceData['exclude_service_title'],
                        'description' => $serviceData['exclude_service_description'],
                    ];
                    $excludes_service_count++;
                }
            }

            // Insert exclude services
            if ($excludes_service_count > 0) {
                ServiceExclude::insert($all_exclude_service);
            }
        }

        // all_faq_service
        if (isset($data['all_faqs_service'])) {
            $new_faqs_services = json_decode($data['all_faqs_service'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Invalid JSON format.',
                    'error' => json_last_error_msg(),
                ], 400);
            }

            // Process each item in the array
            foreach ($new_faqs_services as $serviceData) {
                if (isset($serviceData['faq_service_title'])) {
                    $all_faq_service[] = [
                        'service_id' => $last_service_id,
                        'title' => $serviceData['faq_service_title'],
                        'description' => $serviceData['faq_service_description'],
                    ];
                    $faq_service_count++;
                }
            }

            // Insert faq services
            if ($faq_service_count > 0) {
                ServiceFaq::insert($all_faq_service);
            }
        }

        // all_addons_service
        if (isset($data['all_addons_service'])) {
            $new_addons_services = json_decode($data['all_addons_service'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Invalid JSON format.',
                    'error' => json_last_error_msg(),
                ], 400);
            }

            // Process each item in the array
            foreach ($new_addons_services as $serviceData) {
                if (isset($serviceData['addon_service_title'])) {
                    $all_addons_service[] = [
                        'service_id' => $last_service_id,
                        'title' => $serviceData['addon_service_title'],
                        'price' => $serviceData['addon_service_price'],
                        'quantity' => 1,
                        'image' => $addon_service_image_id ?? null,
                        'description' => $serviceData['addon_service_description'],
                    ];
                    $addons_service_count++;
                }
            }

            // Insert faq services
            if ($addons_service_count > 0) {
                ServiceAddon::insert($all_addons_service);
            }
        }
    }
}
