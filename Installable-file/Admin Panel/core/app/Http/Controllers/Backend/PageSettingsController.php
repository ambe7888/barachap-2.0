<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageSettingsController extends Controller
{
   public function loginRegisterPageSettings(Request $request)
    {
         if($request->isMethod('post')){

            $this->validate($request, [
                'login_form_title' => 'nullable|string',
                'register_page_title' => 'nullable|string',
                'select_terms_condition_page' => 'nullable|string',
                'register_page_description' => 'nullable|string',
                'register_page_image' => 'nullable|string',
                'recaptcha_2_site_key' => 'nullable',
            ]);

            $all_fields = [
                'login_form_title',
                'register_page_title',
                'register_page_description',
                'register_page_image',
                'select_terms_condition_page',
                'register_page_social_login_show_hide',
                'recaptcha_2_site_key',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            return redirect()->back()->with(FlashMsg::settings_update());
        }
        return view('backend.pages.page-settings.login-register-settings');
    }

    public function serviceCreateSettings(Request $request)
    {
         if($request->isMethod('post')){
            $this->validate($request, [
                'service_create_settings' => 'nullable|string',
                'service_create_status_settings' => 'nullable|string'
            ]);

            $all_fields = [
                'service_create_settings',
                'service_create_status_settings'
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            return redirect()->back()->with(FlashMsg::settings_update());
        }

        return view('backend.pages.page-settings.service-create-page-settings');
    }


    public function userPublicProfileSettings(Request $request)
    {
         if($request->isMethod('post')){
            return redirect()->back()->with(FlashMsg::settings_update());
        }

        return view('backend.pages.page-settings.user-public-profile-settings');
    }

    public function adminLoginPageSettings(Request $request)
    {
         if($request->isMethod('post')){
             $this->validate($request, [
                 'admin_login_page_background_image' => 'nullable',
                 'admin_login_page_title' => 'nullable',
                 'admin_login_page_subtitle' => 'nullable'
             ]);
             $all_fields = [
                 'admin_login_page_background_image',
                 'admin_login_page_title',
                 'admin_login_page_subtitle',
             ];
             foreach ($all_fields as $field) {
                 update_static_option($field, $request->$field);
             }

            return redirect()->back()->with(FlashMsg::settings_update());
        }

        return view('backend.pages.page-settings.admin-login-page-settings');
    }

}
