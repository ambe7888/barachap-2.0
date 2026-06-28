<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryListsResource;
use App\Http\Resources\ReasonListsResource;
use App\Http\Resources\Users\ProviderPublicDetailsResource;
use App\Models\Backend\Category;
use App\Models\Backend\Page;
use App\Models\Backend\Reason;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function providerLists(){

        $provider_lists = User::with('reviews')->where('type', 0)
            ->whereHas('services')
            ->latest()
            ->paginate(10);

        if ($provider_lists->isNotEmpty()) {
            return response()->json([
                'provider_lists' => ProviderPublicDetailsResource::collection($provider_lists->items()),
                'pagination' => [
                    'total' => $provider_lists->total(),
                    'count' => $provider_lists->count(),
                    'per_page' => $provider_lists->perPage(),
                    'current_page' => $provider_lists->currentPage(),
                    'last_page' => $provider_lists->lastPage(),
                    'next_page_url' => $provider_lists->nextPageUrl(),
                    'prev_page_url' => $provider_lists->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Provider Not Found.'),
        ]);
    }

    public function contactPage(){
        $contact = Page::select('id', 'slug', 'page_content')->where('slug', 'contact')->first();
        if (!empty($contact)) {
            return response()->json([
                'contact_page' => $contact,
            ]);
        }
        return response()->json([
            'message' => __('Page Not Found.'),
        ]);
    }

    public function termsAndConditionsPage(){
        $terms_and_conditions = Page::select('id', 'slug', 'page_content')->where('slug', 'terms-and-conditions')->first();
        if (!empty($terms_and_conditions)) {
            return response()->json([
                'terms_and_conditions' => $terms_and_conditions,
            ]);
        }
        return response()->json([
            'message' => __('Page Not Found.'),
        ]);
    }

    public function privacyPolicyPage(){
        $privacy_policy = Page::select('id', 'slug', 'page_content')->where('slug', 'privacy-policy')->first();
        if (!empty($privacy_policy)) {
            return response()->json([
                'privacy_policy' => $privacy_policy,
            ]);
        }
        return response()->json([
            'message' => __('Page Not Found.'),
        ]);
    }

    public function reasonLists(){
        $reasons = Reason::select('id', 'title', 'type')
            ->latest()
            ->paginate(10);

        if ($reasons->isNotEmpty()) {
            return response()->json([
                'reasons' => ReasonListsResource::collection($reasons->items()),
                'pagination' => [
                    'total' => $reasons->total(),
                    'count' => $reasons->count(),
                    'per_page' => $reasons->perPage(),
                    'current_page' => $reasons->currentPage(),
                    'last_page' => $reasons->lastPage(),
                    'next_page_url' => $reasons->nextPageUrl(),
                    'prev_page_url' => $reasons->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('reasons Not Found.'),
        ]);

    }

    public function emailVerifyEnableDisable(){
        $email_verify_enable_disable = get_static_option('user_email_verify_enable_disable');
        $status = false;
        if (!empty($email_verify_enable_disable)){
            $status = true;
        }else{
            $status = false;
        }

        return response()->json([
            'verify_enabled' => $status,
        ]);

    }
}
