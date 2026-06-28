<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Services\CalculateTotalHourIncome;
use App\Http\Services\CalculateTotalMonthIncome;
use App\Http\Services\CalculateTotalServiceMonthly;
use App\Http\Services\CalculateTotalServiceWeekly;
use App\Http\Services\CalculateTotalServiceYearly;
use App\Http\Services\CalculateTotalUserMonthly;
use App\Http\Services\CalculateTotalUserWeekly;
use App\Http\Services\CalculateTotalUserYearly;
use App\Http\Services\CalculateTotalWeekIncome;
use App\Http\Services\CalculateTotalYearIncome;
use App\Models\Backend\Admin;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\Language;
use App\Models\Backend\MediaUpload;
use App\Models\Backend\SubCategory;
use App\Models\Order;
use App\Models\Service;
use App\Models\Staff;
use App\Models\SubOrder;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;
use Modules\Coupon\app\Models\Coupon;
use Modules\JobPost\app\Models\JobPost;
use Modules\SupportTicket\app\Models\Ticket;

class AdminDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function adminDashboard()
    {
        // Total Orders
        $totalOrders = Order::count();

        // total tax count
        $total_tax = SubOrder::whereIn('status', [2, 3])
        ->where('payment_status', 'complete')
        ->sum('tax');

        $admin_total_tax = SubOrder::whereIn('status', [2, 3])
         ->whereNotNull('admin_id')
        ->where('payment_status', 'complete')
        ->sum('tax');

        // Total Provider Orders (where admin_id is null and job_post_id is null)
        $totalProviderOrders = Order::whereHas('subOrders', function ($query) {
            $query->whereNull('admin_id');
        })->count();

        // Total Admin Orders (where admin_id is not null OR job_post_id is not null)
        $totalAdminOrders = Order::whereHas('subOrders', function ($query) {
            $query->whereNotNull('admin_id');
        })->count();


        // Total Admin Earnings from their own orders
        $total_admin_earning_with_tax = SubOrder::whereNotNull('admin_id')
            ->whereIn('status', [2, 3])
            ->where('payment_status', 'complete')
            ->sum('total');

       // Total Admin Earnings from provider commissions
        $total_provider_to_admin_earning = SubOrder::whereNotNull('provider_id')
            ->whereIn('status', [2, 3])
            ->where('payment_status', 'complete')
            ->sum('commission_amount');

        $provider_total_withdraw_amount = UserBalance::sum('total_withdrawn');

        $total_admin_earning = $total_admin_earning_with_tax - $admin_total_tax;
        $admin_total_revenue = $total_admin_earning + $total_provider_to_admin_earning;
       
        $dashboardData = [
            ['title' => __('Total Admins'),  'route' => 'admin.all','value' => Admin::count()],
            ['title' => __('Total Users'), 'route' => 'admin.user.all', 'value' => User::count()],
            ['title' => __('Total Client'), 'route' => 'admin.user.all', 'params' => ['status' => '1'], 'value' => User::where('type', 1)->count()],
            ['title' => __('Total Provider'), 'route' => 'admin.user.all', 'params' => ['status' => '0'], 'value' => User::where('type', 0)->count()],
            ['title' => __('Total Services'), 'value' => Service::count()],
            ['title' => __('Total Provider Services'),  'route' => 'admin.user.all.services','value' => Service::providerServices()->count()],
            ['title' => __('Total Admin Services'),  'route' => 'admin.all.services','value' => Service::adminServices()->count()],
            ['title' => __('Total Jobs'),  'route' => 'admin.jobs.all','value' => JobPost::count()],
            ['title' => __('Total Coupons'),  'route' => 'admin.coupon.all','value' => Coupon::count()],
            ['title' => __('Total Orders'), 'value' => $totalOrders],
            ['title' => __('Total Provider Orders'), 'value' => $totalProviderOrders, 'route' => 'admin.user.all.order'],
            ['title' => __('Total Admin Orders'), 'value' => $totalAdminOrders, 'route' => 'admin.service.all.orders'],
            ['title' => __('Total withdraw Request'), 'route' => 'admin.withdraw.request', 'value' => WithdrawRequest::count()],
            ['title' => __('Total withdraw amount'), 'value' => float_amount_with_currency_symbol($provider_total_withdraw_amount)],
            ['title' => __('Total Tax'),'value' => float_amount_with_currency_symbol($total_tax)],
            ['title' => __('Total Admin Earnings from Services'),'value' => float_amount_with_currency_symbol($total_admin_earning)],
            ['title' => __('Total Admin Earning From Commission'),'value' => float_amount_with_currency_symbol($total_provider_to_admin_earning)],
            ['title' => __('Total Admin Revenue'),'value' => float_amount_with_currency_symbol($admin_total_revenue)],
        ];


        $total_user = User::count();
        $recent_users = User::latest()->take(5)->get();
        $total_services = Service::count();
        $recent_services = Service::whereNotNull('provider_id')->latest()->take(5)->get();

        return view('backend.pages.dashboard.dashboard', compact(
            'dashboardData',
            'total_user',
            'recent_users',
            'recent_services',
            'total_services',
        ));
    }

    public function getUserData(Request $request) {
        $interval = $request->input('interval');

        switch ($interval) {
            case '0': // This Week
                $startWeek = Carbon::now()->startOfWeek();
                $endWeek = Carbon::now()->endOfWeek();
                $data=CalculateTotalUserWeekly::calculateUserWeekly($startWeek,$endWeek);
                break;
            case '1': // Last week
                $startWeek = Carbon::now()->startOfWeek();
                $startLastWeek=$startWeek->subweek();
                $endWeek = Carbon::now()->endOfWeek();
                $endLastWeek=$endWeek->subweek();
                $data=CalculateTotalUserWeekly::calculateUserWeekly($startLastWeek,$endLastWeek);
                break;    
            case '2'://this month
                $startMonth = Carbon::now()->startOfMonth();
                $endMonth = Carbon::now()->endOfMonth();
                $data=CalculateTotalUserMonthly::calculateUserMonthly($startMonth,$endMonth);
                break;
            case '3'://last month
                $startMonth = Carbon::now()->subMonth()->startOfMonth();
                $endMonth = Carbon::now()->subMonth()->endOfMonth();
                $data=CalculateTotalUserMonthly::calculateUserMonthly($startMonth,$endMonth);
                break;

            case '4': //  This Yearly
                $currentYear = carbon::now()->year;
                $data=CalculateTotalUserYearly::calculateUserYearly($currentYear);
                break;

            case '5': //  Last Yearly
                $currentYear = carbon::now()->subYear();
                $data=CalculateTotalUserYearly::calculateUserYearly($currentYear);
                break;    
           
            default:
                $data = [];
                $uniqueYears = [];
                break;
        }


        return response()->json($data);
    }

    public function getServiceData(Request $request) {
        $interval = $request->input('interval');

        switch ($interval) {
            case '0': // This Week
                $startWeek = Carbon::now()->startOfWeek();
                $endWeek = Carbon::now()->endOfWeek();
                $data=CalculateTotalServiceWeekly::calculateServiceWeekly($startWeek,$endWeek);
                break;
            case '1': // Last week
                $startWeek = Carbon::now()->startOfWeek();
                $startLastWeek=$startWeek->subweek();
                $endWeek = Carbon::now()->endOfWeek();
                $endLastWeek=$endWeek->subweek();
                $data=CalculateTotalServiceWeekly::calculateServiceWeekly($startLastWeek,$endLastWeek);
                break;    
            case '2'://this month
                $startMonth = Carbon::now()->startOfMonth();
                $endMonth = Carbon::now()->endOfMonth();
                $data=CalculateTotalServiceMonthly::calculateServiceMonthly($startMonth,$endMonth);
                break;
            case '3'://last month
                $startMonth = Carbon::now()->subMonth()->startOfMonth();
                $endMonth = Carbon::now()->subMonth()->endOfMonth();
                $data=CalculateTotalServiceMonthly::calculateServiceMonthly($startMonth,$endMonth);
                break;

            case '4': //  This Yearly
                $currentYear = carbon::now()->year;
                $data=CalculateTotalServiceYearly::calculateServiceYearly($currentYear);
                break;

            case '5': //  Last Yearly
                $currentYear = carbon::now()->subYear();
                $data=CalculateTotalServiceYearly::calculateServiceYearly($currentYear);
                break;    
           
            default:
                $data = [];
                $uniqueYears = [];
                break;
        }        


        return response()->json($data);
    }

    public function getTotalIncomeData(Request $request) {
        $interval = $request->input('interval');
        
        switch ($interval) {
            case '0': // today
                $total_earnings = $this->calculateEarnings('today');
                break;

            case '1': // yesterday
                $total_earnings = $this->calculateEarnings('yesterday');
                break;

            case '2': // recent week
                $total_earnings = $this->calculateEarnings('recent_week');
                break;

            case '3': // last week
                $total_earnings = $this->calculateEarnings('last_week');
                break; 

            case '4': // recent month
                $total_earnings = $this->calculateEarnings('recent_month');
                break;        

            case '5': // last month
                $total_earnings = $this->calculateEarnings('last_month');
                break;

            case '6': // recent year
                $total_earnings = $this->calculateEarnings('recent_year');
                
                break;
            case '7': // last year
                $total_earnings = $this->calculateEarnings('last_year');
                break;    

            default:
                $total_earnings = 0;
                break;
        }

        return response()->json(['total_earnings' => $total_earnings]);
    }

   

    private function calculateEarnings($interval) {            
        switch ($interval) {
            case 'today':
                $today= Carbon::now()->startOfDay();
                $endOfDay=Carbon::now()->endOfDay();
                $result=CalculateTotalHourIncome::calculateHourIncome($today,$endOfDay);
                return $result;
                break;   

            case 'yesterday':
                $today= Carbon::now()->subDay()->startOfDay();
                $endOfDay=Carbon::now()->subDay()->endOfDay();
                $result=CalculateTotalHourIncome::calculateHourIncome($today,$endOfDay);
                return $result;
                break;       

           
            case 'recent_week':
                $startWeek = Carbon::now()->startOfWeek();
                $endWeek = Carbon::now()->endOfWeek();
                $result=CalculateTotalWeekIncome::calculateWeekIncome($startWeek,$endWeek);
                return $result;
                break;  
            
            case 'last_week':
                $startWeek = Carbon::now()->startOfWeek();
                $startLastWeek=$startWeek->subweek();
                $endWeek = Carbon::now()->endOfWeek();
                $endLastWeek=$endWeek->subweek();
                $result=CalculateTotalWeekIncome::calculateWeekIncome($startLastWeek,$endLastWeek);
                return $result;
                break;   
        
            case 'recent_month':
                $startMonth = Carbon::now()->startOfMonth();
                $endMonth = Carbon::now()->endOfMonth();
                $result=CalculateTotalMonthIncome::calculateMonthIncome($startMonth,$endMonth);
                return $result;
                break;     

            case 'last_month':
                $startMonth = Carbon::now()->subMonth()->startOfMonth();
                $endMonth = Carbon::now()->subMonth()->endOfMonth();
                $result=CalculateTotalMonthIncome::calculateMonthIncome($startMonth,$endMonth);
                return $result;
                break;       

            case 'recent_year':
                $recentYear = Carbon::now()->year;
                $result=CalculateTotalYearIncome::calculateYearIncome($recentYear);
                return $result;
                break;

            case 'last_year':
                $lastYear = Carbon::now()->subYear()->year;
                $result=CalculateTotalYearIncome::calculateYearIncome($lastYear);
                return $result;
                break;    
            default:
                return 0;
        }
    }



    public function darkModeToggle(Request $request){
        $data = get_static_option('site_admin_dark_mode');
        if($request->mode == 'off' || empty($data)){
            update_static_option('site_admin_dark_mode','on');
        }
        if($request->mode == 'on'){
            update_static_option('site_admin_dark_mode','off');
        }
        return response()->json(['status'=>'done']);
    }


}
