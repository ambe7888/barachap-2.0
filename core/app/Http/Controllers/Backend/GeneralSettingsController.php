<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Backend\CustomFont;
use App\Models\Backend\Language;
use App\Models\Backend\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Sitemap\SitemapGenerator;
use Xgenious\XgApiClient\Facades\XgApiClient;

class GeneralSettingsController extends Controller
{

    public function siteIdentity()
    {
        return view('backend.general-settings.site-identity');
    }
    public function updateSiteIdentity(Request $request)
    {
        $this->validate($request, [
            'site_logo' => 'nullable|string',
            'site_white_logo' => 'nullable|string',
            'site_favicon' => 'nullable|string',
        ]);

        $fields = [
            'site_logo',
            'site_white_logo',
            'site_favicon',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function updateVersionCheck(Request $request){
        $result = XgApiClient::checkForUpdate(get_static_option("site_license_key"),get_static_option("site_script_version"));
        if (isset($result["success"]) && $result["success"]){
            $productUid = $result['data']['product_uid'] ?? null;
            $clientVersion = $result['data']['client_version'] ?? null;
            $latestVersion = $result['data']['latest_version'] ?? null;
            $productName = $result['data']['product'] ?? null;
            $releaseDate =  $result['data']['release_date'] ?? null;
            $changelog =  $result['data']['changelog'] ?? null;
            $phpVersionReq =  $result['data']['php_version'] ?? null;
            $mysqlVersionReq =  $result['data']['mysql_version'] ?? null;
            $extensions =  $result['data']['extension'] ?? null;
            $isTenant =  $result['data']['is_tenant'] ?? null;
            $daysDiff = $releaseDate;
            $msg = $result['data']['message'] ?? null;

            $output = "";
            $phpVCompare = version_compare(number_format((float) PHP_VERSION, 1), $phpVersionReq == 8 ? '8.0' : $phpVersionReq, '>=');
            $mysqlServerVersion = DB::select('select version()')[0]->{'version()'};
            $mysqlVCompare = version_compare(number_format((float) $mysqlServerVersion, 1), $mysqlVersionReq, '<=');
            $extensionReq = true;
            if ($extensions) {
                foreach (explode(',', str_replace(' ','', strtolower($extensions))) as $extension) {
                    if(!empty($extension)) continue;
                    $extensionReq = XgApiClient::extensionCheck($extension);
                }
            }
            if(($phpVCompare === false || $mysqlVCompare === false) && $extensionReq === false){
                $output .='<div class="text-danger">'.__('Your server does not have required software version installed.  Required: Php'). $phpVersionReq == 8 ? '8.0' : $phpVersionReq .', Mysql'.  $mysqlVersionReq . '/ Extensions:' .$extensions . 'etc </div>';
                return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
            }

            if (!empty($latestVersion)){
                $output .= '<div class="text-success">'.$msg.'</div>';
                $output .= '<div class="card text-center" ><div class="card-header bg-transparent text-warning" >'.__("Please backup your database & script files before upgrading.").'</div>';
                $output .= '<div class="card-body" ><h5 class="card-title" >'.__("new Version").' ('.$latestVersion.') '.__("is Available for").' '.$productName.'!</h5 >';
                $updateActionUrl = route('admin.general.update.download.settings', [$productUid, $isTenant]);
                $output .= '<a href = "#"  class="btn btn-warning" id="update_download_and_run_update" data-version="'.$latestVersion.'" data-action="'.$updateActionUrl.'"> <i class="fas fa-spinner fa-spin d-none"></i>'.__("Download & Update").' </a>';
                $output .= '<small class="text-warning d-block">'.__('it can take upto 5-10min to complete update download and initiate upgrade').'</small></div>';
                $changesLongByLine = explode("\n",$changelog);
                $output .= '<p class="changes-log">';
                $output .= '<strong>'.__("Released:")." ".$daysDiff." " . "</strong><br>";
                $output .= "-------------------------------------------<br>";
                foreach($changesLongByLine as $cg){
                    $output .= $cg."<br>";
                }
                $output .= '</p>';

                $output .='</div>';
            }

            return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
        }

        return response()->json(["msg" => $result["message"],"type" => "danger","markup" => "<p class='text-danger'>".$result["message"]."</p>" ]);

    }

    public function scriptsSettings()
    {
        return view( 'backend.general-settings.thid-party');
    }

    public function updateScriptsSettings(Request $request)
    {

        $this->validate($request, [
            'tawk_api_key' => 'nullable|string',
            'google_adsense_id' => 'nullable|string',
            'site_third_party_tracking_code' => 'nullable|string',
            'site_google_analytics' => 'nullable|string',
            'site_google_captcha_v3_secret_key' => 'nullable|string',
            'site_google_captcha_v3_site_key' => 'nullable|string',
        ]);

        update_static_option('site_disqus_key', $request->site_disqus_key);
        update_static_option('site_google_analytics', $request->site_google_analytics);
        update_static_option('tawk_api_key', $request->tawk_api_key);
        update_static_option('site_third_party_tracking_code', $request->site_third_party_tracking_code);
        update_static_option('site_google_captcha_v3_site_key', $request->site_google_captcha_v3_site_key);
        update_static_option('site_google_captcha_v3_secret_key', $request->site_google_captcha_v3_secret_key);
        update_static_option('facebook_client_id', $request->facebook_client_id);
        update_static_option('facebook_client_secret', $request->facebook_client_secret);
        update_static_option('facebook_callback_url', $request->facebook_callback_url);
        update_static_option('google_adsense_publisher_id', $request->google_adsense_publisher_id);
        update_static_option('google_adsense_customer_id', $request->google_adsense_customer_id);
        update_static_option('google_client_id', $request->google_client_id);
        update_static_option('google_client_secret', $request->google_client_secret);
        update_static_option('google_callback_url', $request->google_callback_url);

        $fields = [
            'site_google_captcha_v3_secret_key',
            'site_google_captcha_v3_site_key',
            'site_third_party_tracking_code',
            'site_google_analytics',
            'tawk_api_key',
            'enable_google_login',
            'google_client_id',
            'google_client_secret',
            'enable_facebook_login',
            'facebook_client_id',
            'facebook_client_secret',
            'google_adsense_publisher_id',
            'google_adsense_customer_id',
            'enable_google_adsense',
            'instagram_access_token',
        ];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }

        return redirect()->back()->with(['msg' => __('Third Party Scripts Settings Updated..'), 'type' => 'success']);
    }

    public function cacheSettings()
    {
        return view('backend.general-settings.cache-settings');
    }

    public function updateCacheSettings(Request $request)
    {
        $this->validate($request, [
            'cache_type' => 'required|string'
        ]);
        if($request->cache_type == 'view'){
            return redirect()->back()->with(['msg' => __('Cache Cleaned'), 'type' => 'success']);
        }
        try{
            Artisan::call($request->cache_type . ':clear');
        }catch(\Exception $e){
            //
        }
        return redirect()->back()->with(['msg' => __('Cache Cleaned'), 'type' => 'success']);
    }

    public function licenseSettings()
    {
        return view('backend.general-settings.license-settings');
    }

    public function updateLicenseSettings(Request $request)
    {
        $this->validate($request, [
            'site_license_key' => 'required|string|max:191',
            'envato_username' => 'required|string|max:191',
        ]);

        $result = XgApiClient::activeLicense($request->site_license_key,$request->envato_username);
        $type = "danger";
        $msg = __("could not able to verify your license key, please try after sometime, if you still face this issue, contact support");
        if (!empty($result["success"]) && $result["success"]){
            update_static_option('site_license_key', $request->site_license_key);
            update_static_option('item_license_status', $result['success'] ? 'verified' : "");
            update_static_option('item_license_msg', $result['message']);
            $type = $result['success'] ? 'success' : "danger";
            $msg = $result['message'];
        }

        return redirect()->back()->with(['msg' => $msg, 'type' => $type]);
    }


    public function softwareUpdateCheckSettings(Request $request){
        return view("backend.general-settings.check-update");
    }

    public function updateVersionCheck(Request $request){
        $licenseKey = trim((string) get_static_option("site_license_key"));
        $licenseStatus = trim((string) get_static_option("item_license_status"));
        $verifiedKey = trim((string) get_static_option("license_verified_key"));
        $hasLocalLicense = ($licenseStatus === 'verified' || !empty($licenseKey) || !empty($verifiedKey));

        try {
            $result = XgApiClient::checkForUpdate($licenseKey, get_static_option("site_script_version"));
        } catch (\Throwable $e) {
            if ($hasLocalLicense) {
                return response()->json([
                    "msg" => __('Your license is already active. Update check is temporarily unavailable.'),
                    "type" => "success",
                    "markup" => "<div class='text-warning'>".__('Your license is already active. Update check is temporarily unavailable.')."</div>"
                ]);
            }

            return response()->json([
                "msg" => __('Unable to verify the update service at the moment. Please try again later.'),
                "type" => "danger",
                "markup" => "<p class='text-danger'>".__('Unable to verify the update service at the moment. Please try again later.')."</p>"
            ]);
        }

        if (isset($result["success"]) && $result["success"]){
            $productUid = $result['data']['product_uid'] ?? null;
            $clientVersion = $result['data']['client_version'] ?? null;
            $latestVersion = $result['data']['latest_version'] ?? null;
            $productName = $result['data']['product'] ?? null;
            $releaseDate =  $result['data']['release_date'] ?? null;
            $changelog =  $result['data']['changelog'] ?? null;
            $phpVersionReq =  $result['data']['php_version'] ?? null;
            $mysqlVersionReq =  $result['data']['mysql_version'] ?? null;
            $extensions =  $result['data']['extension'] ?? null;
            $isTenant =  $result['data']['is_tenant'] ?? null;
            $daysDiff = $releaseDate;
            $msg = $result['data']['message'] ?? null;

            $output = "";
            $phpVCompare = version_compare(number_format((float) PHP_VERSION, 1), $phpVersionReq == 8 ? '8.0' : $phpVersionReq, '>=');
            $mysqlServerVersion = DB::select('select version()')[0]->{'version()'};
            $mysqlVCompare = version_compare(number_format((float) $mysqlServerVersion, 1), $mysqlVersionReq, '<=');
            $extensionReq = true;
            if ($extensions) {
                foreach (explode(',', str_replace(' ','', strtolower($extensions))) as $extension) {
                    if(!empty($extension)) continue;
                    $extensionReq = XgApiClient::extensionCheck($extension);
                }
            }
            if(($phpVCompare === false || $mysqlVCompare === false) && $extensionReq === false){
                $output .='<div class="text-danger">'.__('Your server does not have required software version installed.  Required: Php'). $phpVersionReq == 8 ? '8.0' : $phpVersionReq .', Mysql'.  $mysqlVersionReq . '/ Extensions:' .$extensions . 'etc </div>';
                return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
            }

            if (!empty($latestVersion)){
                $output .= '<div class="text-success">'.$msg.'</div>';
                $output .= '<div class="card text-center" ><div class="card-header bg-transparent text-warning" >'.__("Please backup your database & script files before upgrading.").'</div>';
                $output .= '<div class="card-body" ><h5 class="card-title" >'.__("new Version").' ('.$latestVersion.') '.__("is Available for").' '.$productName.'!</h5 >';
                $updateActionUrl = route('admin.general.update.download.settings', [$productUid, $isTenant]);
                $output .= '<a href = "#"  class="btn btn-warning" id="update_download_and_run_update" data-version="'.$latestVersion.'" data-action="'.$updateActionUrl.'"> <i class="fas fa-spinner fa-spin d-none"></i>'.__("Download & Update").' </a>';
                $output .= '<small class="text-warning d-block">'.__('it can take upto 5-10min to complete update download and initiate upgrade').'</small></div>';
                $changesLongByLine = explode("\n",$changelog);
                $output .= '<p class="changes-log">';
                $output .= '<strong>'.__("Released:")." ".$daysDiff." "."</strong><br>";
                $output .= "-------------------------------------------<br>";
                foreach($changesLongByLine as $cg){
                    $output .= $cg."<br>";
                }
                $output .= '</p>';

                $output .='</div>';
            }

            return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
        }

        if ($hasLocalLicense) {
            return response()->json([
                "msg" => __('Your license is already active. No update information is available right now.'),
                "type" => "success",
                "markup" => "<div class='text-warning'>".__('Your license is already active. No update information is available right now.')."</div>"
            ]);
        }

        return response()->json(["msg" => $result["message"] ?? __('Unable to verify the update service at the moment.'),"type" => "danger","markup" => "<p class='text-danger'>".($result["message"] ?? __('Unable to verify the update service at the moment.'))."</p>" ]);

    }

    public function updateDownloadLatestVersion($productUid, $isTenant){

        $version = \request()->get("version");
        //wrap this function through xgapiclient facades
        $getItemLicenseKey = get_static_option('site_license_key');
        $return_val = XgApiClient::downloadAndRunUpdateProcess($productUid, $isTenant,$getItemLicenseKey,$version);

        if (is_array($return_val)){
            return response()->json(['msg' => $return_val['msg'] , 'type' => $return_val['type']]);
        }elseif (is_bool($return_val) && $return_val){
            return response()->json(['msg' => __('system upgrade success') , 'type' => 'success']);
        }
        //it is false
        return response()->json(['msg' => __('Update failed, please contact support for further assistance') , 'type' => 'danger']);
    }

    public function licenseKeyGenerate(Request $request){
        $request->validate([
            "envato_purchase_code" => "required",
            "envato_username" => "required",
            "email" => "required",
        ]);
        $res = XgApiClient::VerifyLicense(purchaseCode: $request->envato_purchase_code, email: $request->email, envatoUsername: $request->envato_username);
        $type = $res["success"] ? "success" : "danger";
        $message = $res["message"];
        //store information in database
        if (!empty($res["success"])){
            //success verify
            $res["data"] = is_array($res["data"]) ? $res["data"] : (array) $res["data"];
            update_static_option("license_product_uuid",$res["data"]["product_uid"] ?? "");
            update_static_option("license_verified_key",$res["data"]["license_key"] ?? "");
        }
        update_static_option("license_purchase_code",$request->envato_purchase_code);
        update_static_option("license_email",$request->email);
        update_static_option("license_username",$request->envato_username);

        return back()->with(["msg" => $message, "type" => $type]);
    }

    public function databaseUpgrade()
    {
        return view('backend.general-settings.database-upgrade');
    }
    public function databaseUpgradePost(Request $request)
    {
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);
        return back()->with(FlashMsg::item_new('Database Upgraded Successfully'));
    }


}
