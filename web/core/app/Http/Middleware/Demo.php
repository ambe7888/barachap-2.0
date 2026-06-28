<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $api_not_allow_path = [
            'api/v1/client/job/delete',
            'api/v1/client/location/delete',
            'api/v1/client/job/offers/delete',
            'api/v1/client/job/offers/reject',
            'api/v1/client/job/published-status',
            'api/v1/client/job/edit',
            'api/v1/provider/schedule/delete',
            'api/v1/provider/staff/delete',
            'api/v1/provider/service/published-status',
            'api/v1/provider/service/location/update',
            'api/v1/provider/service/edit-service',
            'api/v1/provider/staff/edit',
            'api/v1/user/settings/account-delete',
            'api/v1/user/settings/account-delete',
            'api/v1/user/settings/account-deactive',
            'api/v1/user/change-password',
            'api/v1/reset-password',
            'api/v1/user/change-phone-number',
            'api/v1/user/change-email',

        ];

        $not_allow_path = [
            'admin',
            'user',
        ];
        $allow_path = [
            'api/v1/user/logout',
            'admin',
            'user',
            'broadcasting/auth',
            'admin/notification/send/client',
            'admin/notification/send/provider',
            'api/v1/user/profile/firebase-token',
            'api/v1/user/review-add',
            'api/v1/user/profile/update'
        ];

        $req_path = $request->path();
        if($request->isMethod('POST') || $request->isMethod('PUT')) {
            foreach ($api_not_allow_path as $path) {
                if ($req_path !== 'admin' && strpos($req_path, $path) === 0 && (strlen($req_path) === strlen($path)
                        || preg_match("#^" . preg_quote($path, '#') . "(\/[0-9]+)?$#", $req_path))) {
                    return response()->json([
                        'type' => 'warning',
                        'msg' => __('This is a demo route, changes are not allowed.'),
                      'path' => $req_path
                    ],400);
                }
            }
        }

        $contains = Str::contains($request->path(), $not_allow_path);
        if($request->isMethod('POST') || $request->isMethod('PUT')) {
            $req_path = $request->path();
            foreach ($not_allow_path as $path) {
                // Check if the request path starts with a disallowed path and has an optional ID segment
                if ($req_path !== 'admin' && strpos($req_path, $path) === 0 && (strlen($req_path) === strlen($path)
                        || preg_match("#^" . preg_quote($path, '#') . "(\/[0-9]+)?$#", $req_path))) {
                    // If it's a match, block the request
                    return response()->json([
                        'type' => 'warning',
                        'msg' => 'This is a demo route, changes are not allowed..',
                       'path' => $req_path
                    ],400);
                }
            }

            if($contains && !in_array($request->path(),$allow_path)){
                if ($request->ajax()){
                    // if user profile update
                    if ($request->is('user/profile/edit-profile')){
                        return response()->json([
                            'status'=>'demo_route_on',
                        ]);
                    }
                    return response()->json(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
                }
                toastr_warning('This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.');
                return redirect()->back()->with(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
            }
        }

        $isDemoMiddlewareIsEnabled = true;

        view()->share(['isDemoMiddlewareIsEnabled' => $isDemoMiddlewareIsEnabled]);

        return $next($request);
    }
}
