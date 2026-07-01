<?php

namespace Modules\WhatsAppBookingSystem\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Resources\Orders\OrderDetailsResource;
use App\Http\Services\OrderServiceNotification;
use App\Http\Services\UpdateMainOrderStatus;
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
use Modules\WhatsAppBookingSystem\app\Models\WhatsAppBooking;
use Modules\WhatsAppBookingSystem\app\Models\WhatsAppBookingAddon;
use App\Notifications\OrderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WhatsAppFunctionController extends Controller
{
    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification,UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }
    public function searchService(Request $request,$keyword)
    {
        //return top 10 service
        $keyword = trim($keyword);
        $services=Service::where('title', 'like', "%$keyword%")
            ->get(['id', 'title', 'price']);
        if ($services->isEmpty()) {
            return response()->json(['error' => 'No services found'], 404);
        }
        return response()->json(['data' => $services]);
    }

    public function getServiceAddons(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        $addons = $service->addons;
        return response()->json($addons);
    }

    public function getUserByPhone(Request $request, $phone)
    {
        return User::where('phone', $phone)->first();
    }

    public function serviceDetails(Request $request, $id)
    {
        $service = Service::find($id);

        return response()->json([
            'id' => $service->id,
            'title' => $service->title,
            'price' => $service->price
        ]);
    }
    public function serviceInclude(Request $request, $id)
    {
        $service = Service::with(['includes','excludes'])->find($id);
        return response()->json([
            'included' => $service->includes->pluck('title')->toArray(),
            'excluded' => $service->excludes->pluck('title')->toArray(),
            'service_id' => $service->id,
        ]);
    }

    public function serviceFaq(Request $request, $id)
    {
        $service = Service::with(['faqs'])->find($id);
        return response()->json([
            'service_id' => $service->id,
            'faqs' => $service->faqs->map(function ($faq) {
                return [
                    'title' => $faq->title,
                    'description' => $faq->description,
                ];
            })->toArray(),


        ]);
    }


    public function recentOrderDetails(Request $request,$phone)
    {
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return ['error' => 'User not found'];
        }

        $recentSuborder = SubOrder::where('client_id',$user->id)->latest()->first();
        $recentOrderId= $recentSuborder ? $recentSuborder->order_id : null;
        $recentOrder = Order::where('id', $recentOrderId)->first();
        if (!$recentOrder) {
            return ['error' => 'No recent orders found'];
        }

        return [
            'order_id'      => $recentOrder->id,
            'service_title' => $recentSuborder->service->title ?? 'N/A',
            'price'         => $recentOrder->total ?? 'N/A',
            'status'        => $recentOrder->status ?? 'N/A',
            'payment_gateway' => $recentOrder->payment_gateway ?? 'N/A',
            'payment_status' => $recentOrder->payment_status ?? 'N/A',
            'booked_at'    => $recentSuborder->date,
        ];
    }

    public function orderServiceDetails(Request $request,$phone)
    {
        $client=User::where('phone', $phone)->first();
        $booking = WhatsAppBooking::where('client_id', $client->id)->first();
        $service= Service::find($booking->service_id);
        $staff= Staff::find($booking->staff_id);
        $staff_fullname= $staff ? $staff->first_name . ' ' . $staff->last_name : 'No staff selected';
        $addons_services = WhatsAppBookingAddon::where('whats_app_booking_id', $booking->id)->get();
        $addons=[];
        if ($addons_services->isNotEmpty()) {

            foreach ($addons_services as $addon) {
                $addon_service = ServiceAddon::where('service_id',  $booking->service_id)->where('id', $addon->addon_id)->first();
                $addon_subtotal= $addon->quantity * $addon_service->price;
                $addons[]=[
                    'title' => mb_strimwidth($addon_service?->title, 0, 24, '…'),
                    'quantity' => $addon->quantity,
                    'total' => $addon_subtotal,
                ];

            }

        }

        return [
            'service_title' => mb_strimwidth($service?->title, 0, 24, '…'),
            'addons' => $addons,
            'staff' => mb_strimwidth($staff_fullname, 0, 24, '…'),
        ];

    }

    public function orderOtherDetails(Request $request,$phone)
    {
        $client=User::where('phone', $phone)->first();
        $booking = WhatsAppBooking::where('client_id', $client->id)->first();
        $base_price=0;

        if ($booking->service_id) {

            $service = Service::find($booking->service_id);

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

            }


        }

        $addons_services = WhatsAppBookingAddon::where('whats_app_booking_id', $booking->id)->get();
        $addon_total = 0;
        if ($addons_services->isNotEmpty()) {
            // Prepare for bulk insertion

            foreach ($addons_services as $addon) {
                $addon_service = ServiceAddon::where('service_id',  $booking->service_id)->where('id', $addon->addon_id)->first();
                $addon_subtotal= $addon->quantity * $addon_service->price;
                $addon_total+= $addon_subtotal;
            }

        }

        // base price with  addon price
        $base_price = $base_price + $addon_total;
        $location = UserLocation::where('id', $booking->location_id)
            ->where('user_id', $client->id)
            ->first();

        // tax calculate
        if(!empty($location)){
            $state_tax_rate = calculateTaxBasedOnCoordinates($location->id, 'other');
        }else{
            $state_tax_rate =get_static_option('tax_rate_by_country') ?? 0;
        }

        // Calculate tax amount and  Calculate total price including tax
        $tax_amount = ($base_price * $state_tax_rate) / 100;
        $total = $base_price + $tax_amount;
        $service= Service::find($booking->service_id);

        return [
            'date' => $booking->date,
            'schedule' => $booking->schedule,
            'location' => mb_strimwidth($location->address,0,24,'…'),
            'addon_total' => $addon_total,
            'sub_total' => $base_price,
            'tax' => $tax_amount,
            'total' => $total,
            'payment_gateway' => $booking->payment_gateway ?? 'cash_on_delivery'
        ];

    }



    public function getStaffList(Request $request, $serviceId)
    {
        $service = Service::find($serviceId);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        if($service->allocate_staff==1)
        {
            $staffs_id= explode(',', $service->staffs_id);
            $staffs=Staff::whereIn('id', $staffs_id)->where('status',1)->get(['id','first_name','last_name','email','phone']);

        }
        else{
            if($service->provider_id)
            {
                $staffs=Staff::where('provider_id', $service->provider_id)->where('status',1)->get(['id','first_name','last_name','email','phone']);
            }else{
                $staffs=Staff::where('admin_id', $service->admin_id)->where('status',1)->get(['id','first_name','last_name','email','phone']);
            }
        }


        return response()->json($staffs);
    }

    public function getLocationList(Request $request, $clientId)
    {
        $savedLocations = UserLocation::where('user_id', $clientId)->get();

        return response()->json($savedLocations);

    }

}
