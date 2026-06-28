<?php

namespace Modules\RolePermission\database\seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        // admin dashboard manage
        $admin_dashboard_permissions = [
            'admin-dashboard',
        ];
        foreach ($admin_dashboard_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Admin Dashboard',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // User service manage
        $user_services_permissions = [
            'user-service-list',
            'user-service-approved',
            'user-service-published-status-change',
            'user-service-status-change',
            'user-service-delete',
            'user-service-bulk-delete',
        ];
        foreach ($user_services_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'User Services Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // Admin service manage
        $admin_service_permissions = [
            'admin-service-list',
            'admin-service-add',
            'admin-service-edit',
            'admin-service-delete',
            'admin-service-bulk-delete',
            'admin-service-published-status-change',
            'admin-service-status-change',
        ];
        foreach ($admin_service_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Admin Services Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Report manage
        $report_reason_permissions = [
            'report-reason-list',
            'report-reason-edit',
            'report-reason-delete',
            'report-reason-bulk-delete',
        ];
        foreach ($report_reason_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Reason Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // user manage
        $user_permissions = [
            'user-list',
            'user-add',
            'user-edit',
            'user-status-change',
            'user-verify-status',
            'user-verify-decline',
            'user-password',
            'user-delete',
            'user-permanent-delete',
            'user-deactivated-list',
            'user-deactivated-list',
        ];
        foreach ($user_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'User Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // category manage
        $category_permissions = [
            'category-list',
            'category-add',
            'category-edit',
            'category-status-change',
            'category-delete',
            'category-bulk-delete',
        ];
        foreach ($category_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Category',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // subcategory manage
        $subcategory_permissions = [
            'subcategory-list',
            'subcategory-add',
            'subcategory-edit',
            'subcategory-status-change',
            'subcategory-delete',
            'subcategory-bulk-delete',
        ];
        foreach ($subcategory_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Sub Category',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // child category manage
        $child_category_permissions = [
            'child-category-list',
            'child-category-add',
            'child-category-edit',
            'child-category-status-change',
            'child-category-delete',
            'child-category-bulk-delete',
        ];
        foreach ($child_category_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Child Category',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Dynamic Page manage
        $dynamic_page_permissions = [
            'dynamic-page-list',
            'dynamic-page-add',
            'dynamic-page-edit',
            'dynamic-page-delete',
            'dynamic-page-bulk-delete',
        ];
        foreach ($dynamic_page_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Dynamic Page',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Country Manage
        $country_permissions = [
            'state-list',
            'state-edit',
            'state-status-change',
            'state-csv-file-import',
            'state-delete',
            'state-bulk-delete',
        ];
        foreach ($country_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Country',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        //state Manage
        $state_permissions = [
            'city-list',
            'city-edit',
            'city-status-change',
            'city-csv-file-import',
            'city-delete',
            'city-bulk-delete',
        ];
        foreach ($state_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'State',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // City Manage
        $city_permissions = [
            'area-list',
            'area-edit',
            'area-status-change',
            'area-csv-file-import',
            'area-delete',
            'area-bulk-delete',
        ];
        foreach ($city_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'City',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // department manage
        $department_permissions = [
            'department-list',
            'department-add',
            'department-edit',
            'department-status-change',
            'department-bulk-delete'
        ];
        foreach ($department_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Department Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // Support ticket manage
        $support_ticket_permissions = [
            'support-ticket-list',
            'support-ticket-status-change',
            'support-ticket-details',
            'support-ticket-delete',
            'support-ticket-bulk-delete'
        ];
        foreach ($support_ticket_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Support Ticket',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // All Plugins manage
        $plugins_permissions = [
            'plugins-list',
            'plugins-add',
            'plugins-status-change',
            'plugins-delete',
        ];
        foreach ($plugins_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Plugins Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // All Plugins manage
        $payment_gateway_settings_permissions = [
            'payment-currency-settings'
        ];
        foreach ($payment_gateway_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Payment Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }



        // SMS Gateway Module
        $sms_gateway_settings_permissions = [
            'sms-gateway-settings',
            'sms-gateway-status-change',
            'sms-options-settings',
        ];
        foreach ($sms_gateway_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'SMS Gateway Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Integrations Module
        $integration_settings_permissions = [
            'integration-list',
        ];
        foreach ($integration_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Integrations Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Live Chat Module
        $live_chat_settings_permissions = [
            'live-chat-settings'
        ];
        foreach ($live_chat_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Chat Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // Admin All Notifications
        $admin_notifications_permissions = [
            'notifications-list',
            'notifications-settings',
        ];

        foreach ($admin_notifications_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Admin Notifications',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Google Map Settings
        $google_map_settings_permissions = [
            'google-map-settings',
        ];

        foreach ($google_map_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Google Map Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // appearance Settings
        $appearance_settings_permissions = [
            'color-settings',
            'widgets-list',
            'widgets-add',
            'widgets-delete',
            'media-upload',
            'media-upload-delete',
            '404-page-settings',
            'maintains-page-settings',
        ];

        foreach ($appearance_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Appearance Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // page settings
        $any_page_settings_permissions = [
            'login-register-page-settings',
            'user-public-profile-page-settings',
          ];

        foreach ($any_page_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Page Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // SMTP settings
        $smtp_settings_permissions = [
            'smtp-settings',
          ];

        foreach ($smtp_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'SMTP Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // General Settings
        $general_settings_permissions = [
            'reading-settings',
            'site-identity-settings',
            'basic-settings',
            'seo-settings',
            'scripts-settings',
            'custom-js-settings',
            'gdpr-settings',
            'license-setting',
            'cache-setting',
            'database-upgrade-setting',
            'license-key-generate',
            'update-version-check',
            'software-update-settings',
          ];

        foreach ($general_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'General Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }


        // languages Settings
        $languages_settings_permissions = [
            'languages-list',
            'languages-words-edit',
            'languages-add',
            'languages-delete',
            'languages-clone',
          ];

        foreach ($languages_settings_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Languages Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // job manage
        $job_permissions = [
            'job-list',
            'job-status-change',
            'job-bulk-delete'
        ];
        foreach ($job_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Job Manage',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // provider withdraw manage
        $withdraw_permissions = [
            'withdraw-payment-gateway-list',
            'withdraw-settings-view',
            'withdraw-payment-gateway-add',
            'withdraw-payment-gateway-edit',
            'withdraw-payment-status-change',
            'withdraw-payment-gateway-delete',
            'withdraw-list',
            'withdraw-status-change',
            'withdraw-fee-settings-view',
        ];
        foreach ($withdraw_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Withdraw',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // coupon manage
        $coupon_permissions = [
            'coupon-settings',
            'coupon-list',
            'coupons-new',
            'coupon-edit-add',
            'coupon-delete-add',
        ];
        foreach ($coupon_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Coupons Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Tax settings manage
        $coupon_permissions = [
            'tax-settings',
            'tax-list',
            'tax-new',
            'tax-edit-add',
            'tax-delete-add',
        ];
        foreach ($coupon_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Tax Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Tax settings manage
        $slider_permissions = [
            'slider-settings',
            'slider-list',
            'slider-new',
            'slider-edit-add',
            'slider-delete-add',
        ];
        foreach ($slider_permissions as $permission) {
            Permission::updateOrCreate([
                'menu_name' => 'Slider Settings',
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

    }
}
