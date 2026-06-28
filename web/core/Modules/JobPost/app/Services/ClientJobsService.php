<?php

namespace Modules\JobPost\app\Services;


use App\Actions\Media\MediaHelper;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostLocation;
use App\Models\UserLocation;

class ClientJobsService
{
    public function createJob(Request $request): JobPost
    {
        
        $client = Auth::guard('sanctum')->user();

        // Generate slug from title if not provided
        $slug = $request->filled('slug') ? $request->slug : $request->title;
        $generated_slug = Str::slug(purify_html($slug));
        $originalSlug = $generated_slug;
        $counter = 1;
        if (!empty($generated_slug)){
            while (JobPost::where('slug', $generated_slug)->exists()) {
                $generated_slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $galleryImagesArray = array_map('trim', explode(',', $request->gallery_images));
        $gallery_images = is_array($galleryImagesArray) ? implode('|', $galleryImagesArray) : $galleryImagesArray;

        // check service approve and pending status
        $job_status = get_static_option('job_create_settings') == 'inactive' ? 0 : 1;

        // Create a new Service instance
        $job = JobPost::create([
            'client_id' => $client->id,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id,
            'title' => $request->title,
            'slug' => $generated_slug,
            'description' => $request->description,
            'budget' => $request->budget,
            'gallery_images' => $gallery_images ?? null,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $job_status,
            'date' => $request->date,
            'time' => $request->time,
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
        $last_job_post_id = $job->id;

        // create meta meta data
        try {
            $job->metaData()->create($Metas);
        }catch (\Exception $exception){
        }

        $user_location = UserLocation::where('user_id', $client->id)->where('id',$request->address)->first();

        JobPostLocation::create([
            'job_post_id'=>  $last_job_post_id,
            'user_location_id'=> $user_location?->id,
            'state_id'=> $user_location?->state_id,
            'city_id'=> $user_location?->city_id,
            'area_id'=>  $user_location?->area_id,
            'phone'=> $user_location?->phone,
            'post_code'=> $user_location?->post_code,
            'address'=> $user_location?->address,
            'latitude'=> $user_location?->latitude,
            'longitude'=> $user_location?->longitude,
        ]);

        $message=__('A new job (#:job_id) has been created', ['job_id' => $last_job_post_id]);
        //create service notification to admin
        AdminNotification::create([
            'identity'=> $last_job_post_id,
            'user_id'=> $client->id,
            'type'=>'job-created',
            'message'=>$message,
        ]);

        // get job
        $job = JobPost::with('job_location', 'job_offers')->find($last_job_post_id);

        try {
            $message = get_static_option('job_create_message') ?? 'New Job Create';
            $message = str_replace(["@job_post_id"],[$last_job_post_id],$message);
             $subject =   get_static_option('job_create_subject') ?? __('New Job Post Created');
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        return $job;

    }

    public function editJob(Request $request, $jobId): JobPost
    {


        $client = Auth::guard('sanctum')->user();
        // Find the job post to update
        $job = JobPost::findOrFail($jobId);

        // Generate slug from title if not provided
        $slug = $request->filled('slug') ? $request->slug : $request->title;
        $generated_slug = Str::slug(purify_html($slug));
        $originalSlug = $generated_slug;
        $counter = 1;
        if (!empty($generated_slug)){
            while (JobPost::where('slug', $generated_slug)->where('id', '<>', $jobId)->exists()) {
                $generated_slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }


        $galleryImagesArray = array_map('trim', explode(',', $request->gallery_images));
        $gallery_images = is_array($galleryImagesArray) ? implode('|', $galleryImagesArray) : $galleryImagesArray;


        // check service approve and pending status
        $service_status = !empty(get_static_option('job_create_settings')) ? 1 : 0;

        // Update the job post
        $job->update([
            'client_id' => $client->id,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id,
            'title' => $request->title,
            'slug' => $generated_slug,
            'description' => $request->description,
            'budget' => $request->budget,
            'gallery_images' => $gallery_images ?? null,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $service_status,
            'date' => $request->date,
            'time' => $request->time,
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
        $last_job_post_id = $job->id;

        // create meta meta data
        try {
            $job->metaData()->create($Metas);
        }catch (\Exception $exception){
        }

        $user_location = UserLocation::where('user_id', $client->id)->where('id',$request->address)->first();

        JobPostLocation::updateOrCreate([
            'job_post_id'=>  $last_job_post_id,
        ],
        [
            'user_location_id'=> $user_location?->id,
            'state_id'=> $user_location?->state_id,
            'city_id'=> $user_location?->city_id,
            'area_id'=>$user_location?->area_id,
            'phone'=>$user_location?->phone,
            'post_code'=>$user_location?->post_code,
            'address'=>$user_location?->address,
            'latitude'=>$user_location?->latitude,
            'longitude'=>$user_location?->longitude,
        ]);

        $message=__('A new job (#:job_id) has been created', ['job_id' => $last_job_post_id]);
        
       // create service notification to admin
        
        AdminNotification::create([
            'identity'=> $last_job_post_id,
            'user_id'=> $client->id,
            'type'=>'job-created',
            'message'=>$message,
        ]);

        // get job
        $job = JobPost::with('job_location', 'job_offers')->find($last_job_post_id);

        try {
            $message = get_static_option('job_create_message') ?? 'New Job Create';
            $message = str_replace(["@job_post_id"],[$last_job_post_id],$message);
             $subject =   get_static_option('job_create_subject') ?? __('New Job Post Created');
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        return $job;

    }


}
