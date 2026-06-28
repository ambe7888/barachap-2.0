<?php

namespace Modules\JobPost\app\Http\Controllers\Client;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderServiceNotification;
use App\Jobs\SendOrderCreateEmail;
use App\Models\Backend\AdminCommission;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\SubOrderLocation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\JobPost\app\Models\JobPostOffer;

class ClientJobHireController extends Controller
{
    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function job_hire($job_offer_id,$job_post_id,$selected_payment_gateway){

        $jobOffer = JobPostOffer::find($job_offer_id);


        $offer_details = JobPostOffer::with('job','job_location')->find($job_offer_id);

        // Check if any order has already been created for this job post where the offer is hired (is_hired = 1)
        $jobOfferExistsWithOrder = JobPostOffer::with('sub_orders')->where('job_post_id', $job_post_id)
            ->where('is_hired', 1)
            ->whereHas('sub_orders', function ($query) {
                $query->whereIn('status', [0, 1, 2, 3]);  // Check for statuses 0, 1, 2, or 3 (not canceled)
            })
            ->exists();



        if ($jobOfferExistsWithOrder) {
            return redirect()->back()->with(FlashMsg::item_delete('You have already hired a provider for this job post. You cannot hire another provider.'));
        }

        //commission amount calculate
        $admin_commmission = AdminCommission::first();
        if($admin_commmission->commission_charge_type=='percentage'){
            $commission_amount = ($offer_details->budget * $admin_commmission->commission_charge)/100;
        }else{
            $commission_amount = $admin_commmission->commission_charge;
        }



        //tax amount calculate
        $tax_amount =0;

        $location = $offer_details->job_location;
        // sub order tax calculate
        $state_tax_rate = calculateTaxBasedOnCoordinates($location->id, 'job');
        // tax calculate
        $tax_amount = ($offer_details->budget * $state_tax_rate) / 100;


        $total = $offer_details->budget + $tax_amount;
        $user = auth('sanctum')->user();
        $sub_total = $offer_details->budget;

        // Generate a new invoice number
        $invoiceNumber = generateInvoiceNumber();

        // create job hire order
        $order = Order::create([
            'user_id' => $user->id,
            'sub_total' => $sub_total,
            'tax' => $tax_amount,
            'total' => $total,
            'commission_type' => $admin_commmission->commission_charge_type,
            'commission_charge' => $admin_commmission->commission_charge,
            'commission_amount' => $commission_amount,
            'status' => 0,
            'payment_gateway' => $selected_payment_gateway,
            'payment_status' => 'pending',
            'invoice_number' => $invoiceNumber,
        ]);

        $order_get = Order::find($order->id);
        $last_order_id = $order_get->id;

        $date = $offer_details->job?->date;
        $time = $offer_details->job?->time;

        // sub order create
        $sub_order =  SubOrder::create([
            'order_id' => $last_order_id,
            'job_post_id' => $offer_details->job_post_id,
            'provider_id' => $offer_details->provider_id,
            'client_id' => $user->id,
            'date' => $date,
            'schedule' => $time,
            'basic_price' => $order->sub_total,
            'sub_total' => $order->sub_total,
            'tax' => $order->tax,
            'total' => $order->total,
            'commission_type' => $order->commission_type,
            'commission_charge' => $order->commission_charge,
            'commission_amount' => $order->commission_amount,
            'status' => 0,
        ]);

        // job order location create
        if (!empty($offer_details->job_location)){
            SubOrderLocation::create([
                'sub_order_id' => $sub_order->id,
                'state_id' => $offer_details->job_location?->state_id,
                'city_id' => $offer_details->job_location?->city_id,
                'area_id' => $offer_details->job_location?->area_id,
                'post_code' => $offer_details->job_location?->post_code,
                'address' => $offer_details->job_location?->address,
                'phone' => $offer_details->job_location?->phone,
                'latitude' => $offer_details->job_location?->latitude,
                'longitude' => $offer_details->job_location?->longitude,
            ]);
        }

        // job offer update
        JobPostOffer::where('job_post_id', $sub_order->job_post_id)
            ->where('provider_id', $sub_order->provider_id)
            ->update([ 'is_hired' => 1]);

        //Send order notification to provider
        $provider = User::where('id',$offer_details->provider_id)->first();
        $client_name = Auth::guard('sanctum')->check() ? Auth::guard('sanctum')->user()->fullname : NULL;
        $order_message = __('You have a new order');



        $order_details = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff')->find($last_order_id);

        try {
            // Create order notifications
            $this->orderServiceNotification->createOrderNotification($last_order_id);
            // Dispatch job to send email in the background
            dispatch(new SendOrderCreateEmail($order_details));
        }catch (\Exception $exception){}

        return $order_get;

    }
}
