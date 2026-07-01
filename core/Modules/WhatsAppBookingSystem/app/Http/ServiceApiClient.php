<?php

namespace Modules\WhatsAppBookingSystem\app\Http;

use App\Http\Services\OrderServiceNotification;
use App\Jobs\SendOrderCreateEmail;
use App\Models\Backend\AdminCommission;
use App\Models\Offer;
use App\Models\OfferService;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\Staff;
use App\Models\SubOrder;
use App\Models\SubOrderAddon;
use App\Models\SubOrderLocation;
use App\Models\User;
use App\Models\UserLocation;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\WhatsAppBookingSystem\app\Models\WhatsAppBooking;
use Modules\WhatsAppBookingSystem\app\Models\WhatsAppBookingAddon;

class ServiceApiClient
{

    public function searchServices($keyword)
    {
        $url = route('whatsapp.services.search', ['keyword' => $keyword]);
        return Http::get($url)->json();
    }

    public function getServiceAddons($service_id)
    {
        $url = route('whatsapp.services.addons', ['id' => $service_id]);
        return Http::get($url)->json();
    }

    public function getServiceDetails($id,$phone)
    {
        $url = route('whatsapp.services.details', ['id' => $id]);
        return Http::get($url)->json();
    }

    public function getServiceInclude($phone,$id)
    {
        $url = route('whatsapp.services.includes', ['id' => $id]);
        return Http::get($url)->json();
    }

    public function getServiceFaq($phone,$id)
    {
        $url = route('whatsapp.services.faqs', ['id' => $id]);
        return Http::get($url)->json();
    }

    public function getRecentOrderDetails($phone)
    {
        $url = route('whatsapp.user.recent-order.details', ['phone' => $phone]);
        return Http::get($url)->json();
    }

    public function getUserByPhone($phone)
    {
        $url=route('whatsapp.user.by-phone',['phone'=>$phone]);
        return Http::get($url)->json();
    }

    public function getStaffList($serviceId)
    {
        $url = route('whatsapp.service.staff-lists', ['service_id'=>$serviceId]);
        return Http::get($url)->json();
    }


    public function getLocationList($clientId)
    {
        $url = route('whatsapp.client.location-lists', ['client_id'=>$clientId]);
        return Http::get($url)->json();
    }

    public function getAvailableSlots($phone,$serviceId,$date)
    {
        $service = Service::find($serviceId);
        $dayOfWeek = date('l', strtotime($date));

        if($service->provider_id)
        {
            //check with day
            $all_slots=Schedule::where('provider_id', $service->provider_id)
                ->where('day', $dayOfWeek)
                ->pluck('schedule')
                ->toArray();
        }else{
            $all_slots=Schedule::whereNotNull('admin_id')
                ->where('day', $dayOfWeek )
                ->pluck('schedule')
                ->toArray();
        }
        $bookedSlots = SubOrder::where('service_id', $serviceId)
            ->whereDate('date', $date)
            ->pluck('schedule')
            ->toArray();

        $availableSlots = array_values(array_diff($all_slots, $bookedSlots));

        return $availableSlots;
    }

    public function getOrderServiceDetails($phone)
    {
        $url = route('whatsapp.order.service.details', ['phone' => $phone]);
        return Http::get($url)->json();
    }

    public function getOrderOtherDetails($phone)
    {
        $url = route('whatsapp.order.other.details', ['phone' => $phone]);
        return Http::get($url)->json();
    }


    public function placeOrder($serviceId, $phone)
    {
        $client=User::where('phone', $phone)->first();
        $client_id=$client->id;
        $data=WhatsAppBooking::where('client_id', $client_id)->first();

        if(!$data->location_id)
        {
            return ['error' => 'You have to give your address'];
        }
        else if(!$data->date)
        {
            return ['error' => 'Kindly provide a date on which you would like to receive the service.'];
        }
        else if(!$data->schedule)
        {
            return ['error' => 'You have to give your schedule'];
        }


        $commission = AdminCommission::first();

        // Generate a new invoice number
        $invoiceNumber = generateInvoiceNumber();

        // if order price 0 not create order
        $total_service_amount_check = 0;
        if ($data->service_id) {
            $service = Service::find($data->service_id);
            if ($service) {
                $total_service_amount_check += $service->price;
            }
        }

        if ($total_service_amount_check === 0) {
            return response()->json([
                'error' => __('Service price is 0, order cannot be created. Please try other services.'),
            ], 404);
        }

        // create order
        $order = Order::create([
            'user_id' => $client_id,
            'sub_total' => 0,
            'tax' => 0,
            'total' => 0,
            'status' => 0,
            'payment_gateway' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'invoice_number' => $invoiceNumber,
        ]);


        $last_order_id = $order->id;
        $addons_service_total_price = 0;
        $sub_total = 0;
        $tax_amount =0;
        $total = 0;
        $base_price=0;

        // Create Sub Orders
        if ($data->service_id) {

            $staff = Staff::find($data->staff_id);
            $staff_id = $staff ? $staff->id : null;
            $service = Service::find($data->service_id);
            // if service creator admin add admin_id
            $admin_id = $service->admin_id;

            if (!empty($service))
            {
                $offer_services=OfferService::where('service_id',$service->id)->get();
                if($offer_services->isNotEmpty())
                {

                    foreach($offer_services as $offer_service)
                    {

                        $offer=Offer::where('id',$offer_service->offer_id)->first();
                        if($offer){

                            if($offer->status==1 && strtotime($offer->expires_at)>time())
                            {

                                $base_price = $offer_service->discount_price;
                            }
                            else{
                                // base price calculate

                                if ($service->discount_price > 0)
                                {
                                    // discount price if it is greater than 0
                                    $base_price = $service->discount_price;
                                }
                                else{
                                    // Otherwise regular price
                                    $base_price = $service->price;
                                }
                            }
                        }
                        else{

                            // base price calculate
                            if ($service->discount_price > 0)
                            {
                                // discount price if it is greater than 0
                                $base_price = $service->discount_price;
                            }
                            else{
                                // Otherwise regular price
                                $base_price = $service->price;
                            }

                        }

                    }
                }
                else{

                    // base price calculate
                    if ($service->discount_price > 0){
                        // discount price if it is greater than 0
                        $base_price = $service->discount_price;
                    }else{
                        // Otherwise regular price
                        $base_price = $service->price;
                    }

                }



                $location = UserLocation::where('id', $data->location_id)
                    ->where('user_id', $client_id)
                    ->first();

                $sub_order = SubOrder::create([
                    'order_id' => $last_order_id,
                    'service_id' => $data->service_id,
                    'client_id' => $client_id,
                    'provider_id' => $service->provider_id,
                    'admin_id' => $admin_id,
                    'staff_id' => $staff_id,
                    'date' => $data->date,
                    'schedule' => $data->schedule,
                    'basic_price' => $base_price,
                    'sub_total' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'payment_status' => 'pending',
                    'commission_type' => null,
                    'commission_charge' => 0,
                    'commission_amount' => 0,
                    'order_note' => null,
                ]);

                // send OrderNotification
                $provider = User::where('id', $service->provider_id)->first();
                $order_message = __('You have a new order');
                if (!empty($provider)){
                    $provider->notify(new OrderNotification($last_order_id, $data->service_id, $provider->id, $client_id, $order_message));
                }

                // incrementer service sold_count
                Service::where('id', $sub_order->service_id)->increment('sold_count', 1);

                // sub order location create
                if (!empty($location)){
                    SubOrderLocation::create([
                        'sub_order_id' => $sub_order->id,
                        'state_id' => $location->state_id,
                        'city_id' => $location->city_id,
                        'area_id' => $location->area_id,
                        'title' => $location->title,
                        'post_code' => $location->post_code,
                        'address' => $location->address,
                        'phone' => $location->phone,
                        'emergency_phone' => $location->emergency_phone,
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'type' => $location->type,
                    ]);
                }

            }


        }

        $addons_services = WhatsAppBookingAddon::where('whats_app_booking_id', $data->id)->get();
        // Sub Order addons create
        if ($addons_services->isNotEmpty()) {
            // Prepare for bulk insertion
            $sub_order_addons = [];
            foreach ($addons_services as $addon) {
                $addon_service = ServiceAddon::where('service_id',  $data->service_id)->where('id', $addon->addon_id)->first();
                $sub_order_id = SubOrder::where('order_id', $last_order_id)->where('service_id', $data->service_id)->first();

                if (!empty($addon_service)) {
                    $addons_service_total_price = $addon->quantity * $addon_service->price;
                    $sub_order_addons[] = [
                        'sub_order_id' => $sub_order_id->id,
                        'title' => $addon_service->title,
                        'price' => $addon_service->price,
                        'total' => $addons_service_total_price, // Total price for this specific add-on
                        'quantity' => $addon->quantity,
                    ];
                }
            }

            // Remove duplicates by creating a unique array of sub_order_id and addon_id combinations
            $unique_sub_order_addons = array_unique($sub_order_addons, SORT_REGULAR);
            if (!empty($unique_sub_order_addons)) {
                DB::table('sub_order_addons')->insert($unique_sub_order_addons);
            }
        }


        // order wise sub order get
        $suborder = SubOrder::with(['subOrderLocations'])->where('order_id', $last_order_id)->first();

        // for main order update
        $sub_total_for_main_order = 0;
        $coupon_amount_for_main_order = 0;
        $tax_amount_for_main_order = 0;
        $total_for_main_order = 0;
        $commission_amount_for_main_order = 0;


        $suborder_addon = SubOrderAddon::where('sub_order_id', $suborder->id)->sum('total');
        // base price with sub suborder addon price
        $base_price = $suborder->basic_price + $suborder_addon;
        $location = SubOrderLocation::where('sub_order_id', $suborder->id)->first();

        // sub order tax calculate
        if(!empty($location)){
            $state_tax_rate = calculateTaxBasedOnCoordinates($location->id, 'order');
        }else{
            $state_tax_rate =get_static_option('tax_rate_by_country') ?? 0;
        }

        // Calculate commission amount
        $commission_amount = 0;
        if ($commission->commission_charge_type == 'percentage') {
            $commission_amount = ($base_price * $commission->commission_charge) / 100;
        } else{
            $commission_amount = $commission->commission_charge;
        }


        // Calculate tax amount and  Calculate total price including tax
        $tax_amount = ($base_price * $state_tax_rate) / 100;
        $total = $base_price + $tax_amount;


        if (!empty($suborder)){
            // Update suborder
            SubOrder::where('id', $suborder->id)->update([
                'sub_total' => $base_price,
                'tax' => $tax_amount,
                'total' => $total,
                'commission_type' => $commission->commission_charge_type,
                'commission_charge' => $commission->commission_charge,
                'commission_amount' => $commission_amount,
            ]);

            // main order for sub amount
            $sub_total_for_main_order += $base_price;
            $tax_amount_for_main_order += $tax_amount;
            $total_for_main_order += $total;

            // main order total commission calculate
            $commission_amount_for_main_order += $commission_amount;
        }


        // update order after completed sub-order
        Order::where('id', $last_order_id)->update([
            'sub_total' => $sub_total_for_main_order,
            'tax' => $tax_amount_for_main_order,
            'total' => $total_for_main_order,
            'commission_type' => $commission->commission_charge_type,
            'commission_charge' => $commission->commission_charge,
            'commission_amount' => $commission_amount_for_main_order,
        ]);

        $order_details = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service', 'subOrders.job', 'subOrders.reviews', 'subOrders.order_complete_request')->find($last_order_id);

        $sub_order=SubOrder::where('order_id', $last_order_id)
            ->first();

        try {
            // Create order notifications
            $orderNotification = new OrderServiceNotification();
            $orderNotification->createOrderNotification($last_order_id);

            // Dispatch job to send email in the background
            dispatch(new SendOrderCreateEmail($order_details));
        }catch (\Exception $exception){

        }

        $data=WhatsAppBooking::where('client_id', $client_id)->first();
        $addons= WhatsAppBookingAddon::where('whats_app_booking_id', $data->id)->get();
        // delete  addons then data
        if($addons->isNotEmpty())
        {
            foreach ($addons as $addon) {
                $addon->delete();
            }
        }
        $data->delete();


              return [
                    'order_id'      => $order_details->id,
                    'service_title' => $sub_order->service?->title ?? 'N/A',
                    'price'         => $order_details->total ?? 'N/A',
                    'status'        => $order_details->status ?? 'N/A',
                    'payment_gateway' => $order_details->payment_gateway ?? 'N/A',
                    'payment_status' => $order_details->payment_status ?? 'N/A',
                ];

    }

}
