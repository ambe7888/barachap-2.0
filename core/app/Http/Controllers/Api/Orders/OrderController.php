<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Resources\Orders\OrderDetailsResource;
use App\Http\Services\OrderServiceNotification;
use App\Jobs\SendOrderCreateEmail;
use App\Models\Backend\AdminCommission;
use App\Models\Offer;
use App\Models\OfferService;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\Staff;
use App\Models\SubOrder;
use App\Models\SubOrderAddon;
use App\Models\SubOrderLocation;
use App\Models\User;
use App\Models\UserLocation;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;
use Modules\Tax\app\Models\CityTax;
use Modules\Tax\app\Models\StateTax;
use App\Models\OrderCancellationPolicy;
use Illuminate\Support\Carbon;
use App\Models\RefundedOrder;
use App\Jobs\SendOrderCancelEmail;
use App\Http\Services\UpdateMainOrderStatus;

class OrderController extends Controller
{

    protected $orderServiceNotification;
    protected $updateMainOrderStatus;

    public function __construct(OrderServiceNotification $orderServiceNotification,UpdateMainOrderStatus $updateMainOrderStatus)
    {
        $this->orderServiceNotification = $orderServiceNotification;
        $this->updateMainOrderStatus = $updateMainOrderStatus;
    }

    public function serviceOrderCreate(Request $request)
    {

        if (Auth::guard('sanctum')->user()->type === 0){
            return response()->json([
                'message' => __('Order Create Only Client.')
            ], 429);
        }

        // Extract the data
        $data = $request->all();
        // Convert JSON strings to arrays if needed
        if (isset($data['all_services']) && is_string($data['all_services'])) {
            $data['all_services'] = json_decode($data['all_services'], true);
        }

        if (isset($data['addons_services']) && is_string($data['addons_services'])) {
            $data['addons_services'] = json_decode($data['addons_services'], true);
        }

        // Flatten nested data if necessary
        if (isset($data['all_services']['all_services'])) {
            $data['all_services'] = $data['all_services']['all_services'];
        }

        if (isset($data['addons_services']['addons_services'])) {
            $data['addons_services'] = $data['addons_services']['addons_services'];
        }

        // Validate the transformed data using OrderCreateRequest
        $orderCreateRequest = new OrderCreateRequest();
        $validator = Validator::make($data, $orderCreateRequest->rules());

        // Apply conditional validation
        $orderCreateRequest->withValidator($validator);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('Validation failed'),
                'errors' => $validator->errors()
            ], 422);
        }


         $commission = AdminCommission::first();
        if ($request->selected_payment_gateway == 'cash_on_delivery' || $request->selected_payment_gateway == 'manual_payment') {
            $payment_status = 'pending';
        } else {
            $payment_status = 'pending';
        }

        $user_id = Auth::guard('sanctum')->user()->id;
        $payment_gateway_name = $request->selected_payment_gateway;


         // Generate a new invoice number
        $invoiceNumber = generateInvoiceNumber();

        // if order price 0 not create order
        $total_service_amount_check = 0;
        if (isset($request->all_services)) {
            $all_services = !empty($request->all_services) ? json_decode($request->all_services, true) : (object)[];
            foreach ($all_services['all_services'] as $single_service) {
                $service = Service::find($single_service['service_id']);
                if ($service) {
                    $total_service_amount_check += $service->price;
                }
            }
        }

        if ($total_service_amount_check == 0) {
            return response()->json([
                'message' => __('Service price is 0, order cannot be created. Please try other services.'),
            ], 400);
        }

        // create order
       $order = Order::create([
            'user_id' => $user_id,
            'sub_total' => 0,
            'tax' => 0,
            'total' => 0,
            'status' => 0,
            'payment_gateway' => $payment_gateway_name,
            'payment_status' => $payment_status,
            'invoice_number' => $invoiceNumber,
        ]);


        $last_order_id = $order->id;
        $addons_service_total_price = 0;
        $sub_total = 0;
        $tax_amount =0;
        $total = 0;
        $base_price=0;

        // Create Sub Orders
        if (isset($request->all_services)) {
            $all_services = !empty($request->all_services) ? json_decode($request->all_services, true) : (object)[];

                foreach ($all_services['all_services'] as $single_service) {
                      // if service creator admin add admin_id
                    $admin_id = $service->admin_id;

                    $staff = Staff::find($single_service['staff_id']);
                    $staff_id = $staff ? $staff->id : null;
                    $service = Service::find($single_service['service_id']);



                    if (!empty($service))
                    {

                        $order_services=OfferService::where('service_id',$single_service['service_id'])->get();
                        if($order_services->isNotEmpty())
                        {

                            foreach($order_services as $order_service)
                            {

                                $offer=Offer::where('id',$order_service->offer_id)->first();
                                if($offer){

                                    if($offer->status==1 && strtotime($offer->expires_at)>time())
                                    {

                                        $base_price = $order_service->discount_price;
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



                    $location = UserLocation::where('id', $single_service['location_id'])
                        ->where('user_id', $user_id)
                        ->first();

                       $sub_order = SubOrder::create([
                             'order_id' => $last_order_id,
                             'service_id' => $single_service['service_id'],
                             'client_id' => $user_id,
                             'provider_id' => $service->provider_id,
                             'admin_id' => $admin_id,
                             'staff_id' => $staff_id,
                             'date' => $single_service['date'],
                             'schedule' => $single_service['schedule'],
                             'basic_price' => $base_price,
                             'sub_total' => 0,
                             'tax' => 0,
                             'total' => 0,
                             'payment_status' => $payment_status,
                             'commission_type' => null,
                             'commission_charge' => 0,
                             'commission_amount' => 0,
                             'order_note' => $single_service['order_note'] ?? null,
                         ]);

                      // send OrderNotification
                        $provider = User::where('id', $service->provider_id)->first();
                        $order_message = __('You have a new order');
                         if (!empty($provider)){
                             $provider->notify(new OrderNotification($last_order_id, $single_service['service_id'], $provider->id, $user_id, $order_message));
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
        }



        // Retrieve the raw input data from the request
        $all_services_json = $request->input('all_services', '[]');
        $addons_services_json = $request->input('addons_services', '[]');

      // Decode the JSON strings into PHP arrays
        $all_services_data = json_decode($all_services_json, true);
        $addons_services_data = json_decode($addons_services_json, true);

        // Extract all_services data if it exists
        if (is_array($all_services_data) && isset($all_services_data['all_services'])) {
            $all_services = $all_services_data['all_services'];
        } else {
            // Handle if the format is not as expected or all_services key is missing
            $all_services = [];
        }

      // Extract addons_services data if it exists
        if (is_array($addons_services_data) && isset($addons_services_data['addons_services'])) {
            $addons_services = $addons_services_data['addons_services'];
        } else {
            // Handle if the format is not as expected or addons_services key is missing
            $addons_services = [];
        }


        // Sub Order addons create
        if (isset($request->addons_services)) {
            // Decode addons_services JSON data
            $addons_services = !empty($request->addons_services) ? json_decode($request->addons_services, true) : [];
            // Prepare for bulk insertion
            $sub_order_addons = [];
            foreach ($addons_services['addons_services'] as $addon) {
                $addon_service = ServiceAddon::where('service_id',  $addon['service_id'])->where('id', $addon['addon_service_id'])->first();
                $sub_order_id = SubOrder::where('order_id', $last_order_id)->where('service_id', $addon['service_id'])->first();

                if (!empty($addon_service)) {
                    $addons_service_total_price = $addon['quantity'] * $addon_service->price;
                        $sub_order_addons[] = [
                            'sub_order_id' => $sub_order_id->id,
                            'title' => $addon_service->title,
                            'price' => $addon_service->price,
                            'total' => $addons_service_total_price, // Total price for this specific add-on
                            'quantity' => $addon['quantity'],
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
        $suborders = SubOrder::with(['subOrderLocations'])->where('order_id', $last_order_id)->get();

        // for main order update
        $sub_total_for_main_order = 0;
        $coupon_amount_for_main_order = 0;
        $tax_amount_for_main_order = 0;
        $total_for_main_order = 0;
        $commission_amount_for_main_order = 0;

        foreach ($suborders as $suborder){
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

            // apply for suborder coupons
            $coupon_details = subOrderCalculateCouponAmount($request->coupon_code, $base_price);

            // Calculate tax amount and  Calculate total price including tax
            $tax_amount = ($base_price * $state_tax_rate) / 100;
            $total = $coupon_details['total'] + $tax_amount;


            if (!empty($suborder)){
                // Update suborder
                SubOrder::where('id', $suborder->id)->update([
                    'sub_total' => $base_price,
                    'coupon_amount' => $coupon_details['coupon_amount'],
                    'tax' => $tax_amount,
                    'total' => $total,
                    'commission_type' => $commission->commission_charge_type,
                    'commission_charge' => $commission->commission_charge,
                    'commission_amount' => $commission_amount,
                ]);

                // main order for sub amount
                $sub_total_for_main_order += $base_price;
                $coupon_amount_for_main_order += $coupon_details['coupon_amount'];
                $tax_amount_for_main_order += $tax_amount;
                $total_for_main_order += $total;

                // main order total commission calculate
                $commission_amount_for_main_order += $commission_amount;
            }
        }

        // if manual payment
        if($request->selected_payment_gateway === 'manual_payment') {
            if ($image = $request->file('manual_payment_image')) {
                $imageName = 'manual_attachment_'.time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move('assets/uploads/manual-payment', $imageName);
                Order::where('id',$last_order_id)->update([
                    'payment_attachment' =>$imageName
                ]);
            }
        }

        // apply for suborder coupons
        $main_coupon_details = subOrderCalculateCouponAmount($request->coupon_code, $total);

        // update order after completed sub-order
        Order::where('id', $last_order_id)->update([
            'sub_total' => $sub_total_for_main_order,
            'coupon_code' => $main_coupon_details['coupon_code'] ?? null,
            'coupon_type' => $main_coupon_details['coupon_type'] ?? null,
            'coupon_amount' =>  $coupon_amount_for_main_order,
            'tax' => $tax_amount_for_main_order,
            'total' => $total_for_main_order,
            'commission_type' => $commission->commission_charge_type,
            'commission_charge' => $commission->commission_charge,
            'commission_amount' => $commission_amount_for_main_order,
        ]);

        $order_details = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service', 'subOrders.job', 'subOrders.reviews', 'subOrders.order_complete_request')->find($last_order_id);

        try {
            // Create order notifications
            $this->orderServiceNotification->createOrderNotification($last_order_id);
            // Dispatch job to send email in the background
            dispatch(new SendOrderCreateEmail($order_details));
            if(moduleExists('WhatsAppBookingSystem'))
            {
                // Send WhatsApp message
                $receiverNumber = Auth::guard('sanctum')->user()->phone;
                $message = __('You have successfully placed an order #') . $last_order_id;
                dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));

                // Get service IDs for the given order
                $orders_services_ids = SubOrder::where('order_id', $order_details->id)
                    ->pluck('service_id');

                // Get unique provider IDs
                $service_provider_ids = Service::whereIn('id', $orders_services_ids)
                    ->whereNotNull('provider_id')
                    ->pluck('provider_id')
                    ->unique();

                // Get unique provider emails
                $providers = User::whereIn('id', $service_provider_ids)->get();
                // Send order email to provider
                if ($providers->isNotEmpty()) {
                    foreach ($providers as $provider) {
                        $receiverNumber = $provider?->phone;
                        $message = __('You have a new order #') . $last_order_id;
                        dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));
                    }
                }
            }
        }catch (\Exception $exception){

        }


        return response()->json([
            'order_details'=> new OrderDetailsResource($order_details),
        ]);
    }

    public function paymentStatusUpdate(Request $request){

        // Ensure the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized access')
            ], 401);
        }

        // Retrieve the authenticated user's email
        $clientEmail = Auth::guard('sanctum')->user()->email;
        $receivedHmac = $request->header('X-HMAC');
        // Define the secret key (must match the one used by the client)
        $secretKey = 'e2b8c14a6f8b6d4f9c5f6e8b0d4c1a6e0b9c7f5a2e6b4d8c7a1e3f4d6b8c5f9';
        // Generate the HMAC on the server side using the client's email
        $calculatedHmac = hash_hmac('sha256', $clientEmail, $secretKey);
        // Verify if the HMAC matches
        if ($receivedHmac !== $calculatedHmac) {
            return response()->json([
                'message' => __('Unauthorized access')
            ], 403); // Forbidden
        }

        $request->validate([
            'order_id' => 'required|integer'
        ]);

        // payment status update after order create
        $order_details = Order::find($request->order_id);

        if (empty($order_details)) {
            return response()->json([
                'message' => __('Order not found')
            ], 404);
        }

        $order_details->payment_status = 'complete';
        $order_details->save();

        // Update the payment status of all suborders related to this order
        SubOrder::where('order_id', $request->order_id)->update(['payment_status' => 'complete']);

        if($request->has('job_post_id') && $request->job_post_id === $order_details->job_post_id){
            JobPostOffer::where('id',$request->job_post_id)->update([
                'is_hired' => 1,
                'status' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('payment status update success')
        ]);

    }

    public function cancelOrder(Request $request){
        // Ensure the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => __("Unauthorized access")
            ], 401);
        }

        $order_id=$request->order_id;
        $sub_order_id=json_decode($request->sub_order_ids,true);
        $cancel_reason=$request->cancel_reason;
        $gateway_id=$request->gateway_id;
        $gateway_fields=$request->gateway_fields;
        $refunded_amount=0;
        if(!$order_id)
        {
            return response()->json([
               'success' => false,
               'message' => __("Order id required")
            ], 400);
        }
        if(empty($sub_order_id))
        {
            return response()->json([
                'success' => false,
                'message' => __("Sub order id required")
            ], 400);
        }

        $sub_order_ids=[];
        $items=[];
        foreach($sub_order_id as $key=>$value)
        {
            $sub_order_ids[]=$value;
        }

        $time_expired="true";
        $message="";
        $success="";

        $cancellation_policy=OrderCancellationPolicy::first();
        if(empty($cancellation_policy))
        {
            return response()->json([
                'success' => false,
                'message' => __("Cancellation policy not found")
            ], 400);
        }
        $available_type=$cancellation_policy->available_type;
        $order=Order::where('id',$order_id)->first();
        if($available_type=='certain_time')
        {

            $cancel_time=$cancellation_policy->time_in_min;
            $current_time=Carbon::now();
            $order_created_time=Carbon::parse($order->created_at);
            $diff_in_minutes=$current_time->diffInMinutes($order_created_time);
            if($diff_in_minutes<=$cancel_time)
            {
                $time_expired="false";

            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => __("You can cancel the order within ".$cancel_time." minutes")
                ], 400);
            }
        }



        $sub_order_details=SubOrder::whereIn("id",$sub_order_ids)->get();
        if($sub_order_details->isNotEmpty())
        {

            foreach($sub_order_details as $sub_order)
            {
                $old_status=$sub_order->status;
                if($sub_order->status== 4)
                {
                    return response()->json([
                        'success' => false,
                        'message' => __("Order for this ".$sub_order->service?->title." already canceled")
                    ], 400);
                }
                else if(($sub_order->payment_status=='complete' && $sub_order->status != 2 && $sub_order->status != 3))
                {
                    if($available_type=='certain_time')
                    {
                        if($time_expired =="false")
                        {
                            if( $cancellation_policy->fine_type =="flat")
                            {
                                $refunded_amount=$sub_order->total - $cancellation_policy->amount;
                            }
                            else if( $cancellation_policy->fine_type =="percentage")
                            {
                                $refunded_amount=$sub_order->total - ($sub_order->total * $cancellation_policy->amount/100);
                            }
                            $sub_order->status=4;
                            $sub_order->is_refunded=1;
                            $sub_order->save();
                            // update main order status
                            $this->updateMainOrderStatus->execute($order, $sub_order);

                            $refunded_order=RefundedOrder::updateOrCreate([
                                'order_id'=>$sub_order->order_id,
                                'sub_order_id'=>$sub_order->id,
                                'client_id'=>$sub_order->client_id,
                                'provider_id'=>$sub_order->provider_id,
                                'admin_id'=>$sub_order->admin_id,

                                ],[

                                'amount'=>$refunded_amount,
                                'gateway_id'=>$gateway_id,
                                'gateway_fields'=>$gateway_fields,
                                'cancel_reason'=>$cancel_reason ?? "",
                                'user_type'=> 1,
                                'current_suborder_status' => $old_status,
                                'type'=> 0,
                                'status'=> 0,
                                'add_fee' => 1
                            ]);
                            $messgae="Order Cancel successfully";
                            $success="true";
                            $items[]=$sub_order->id;

                        }
                    }
                    else
                    {

                        if( $cancellation_policy->fine_type =="flat")
                        {
                            $refunded_amount=$sub_order->total - $cancellation_policy->amount;
                        }
                        else if( $cancellation_policy->fine_type =="percentage")
                        {
                            $refunded_amount=$sub_order->total - ($sub_order->total * $cancellation_policy->amount/100);
                        }
                        $sub_order->status=4;
                        $sub_order->is_refunded=1;
                        $sub_order->save();
                        // update main order status
                        $this->updateMainOrderStatus->execute($order, $sub_order);
                        $refunded_order=RefundedOrder::updateOrCreate(
                            [
                                'order_id'=>$sub_order->order_id,
                                'sub_order_id'=>$sub_order->id,
                                'client_id'=>$sub_order->client_id,
                                'provider_id'=>$sub_order->provider_id,
                                'admin_id'=>$sub_order->admin_id,
                            ],[

                            'amount'=>$refunded_amount,
                            'gateway_id'=>$gateway_id,
                            'gateway_fields'=>$gateway_fields,
                            'cancel_reason'=>$cancel_reason ?? "",
                            'user_type'=> 1,
                            'current_suborder_status' => $old_status,
                            'type'=> 0,
                            'status'=> 0,
                            'add_fee' => 1
                        ]);
                        $messgae="Order Cancel successfully";
                        $success="true";
                        $items[]=$sub_order->id;
                    }
                }
                else if(($sub_order->payment_status!='complete' && $sub_order->status != 2 && $sub_order->status != 3))

                {
                    if($available_type=='certain_time')
                    {

                        if($time_expired=="false")
                        {
                            $sub_order->status=4;
                            $sub_order->is_refunded=0;
                            $sub_order->save();
                            // update main order status
                            $this->updateMainOrderStatus->execute($order, $sub_order);

                            $message="Order Cancel successfully";
                            $success="true";
                            $items[]=$sub_order->id;

                        }


                    }
                    else
                    {
                        $sub_order->status=4;
                        $sub_order->is_refunded=0;
                        $sub_order->save();
                        // update main order status
                        $this->updateMainOrderStatus->execute($order, $sub_order);

                        $message="Order Cancel successfully";
                        $success="true";
                        $items[]=$sub_order->id;

                    }


                }
                else
                {
                    $message="Order for this ".$sub_order->service?->title."already completed or canceled";
                    $success="false";
                    return response()->json([
                        'success' => $success,
                        'message' => $message
                    ], 400);
                }


            }
            if($success=="true")
            {

                    try {

                        // Cancel order notifications
                       $this->orderServiceNotification->cancelOrderNotification($order_id,$items,$request);
                        // Dispatch job to send email in the background
                        $order_details = Order::with('user','subOrders')->find($order_id);
                        dispatch(new SendOrderCancelEmail($order_details,$items));


                    }catch (\Exception $exception){


                    }

                    try{
                        if(moduleExists('WhatsAppBookingSystem'))
                        {
                            //get service title for those are cancel
                            $suborder_items = SubOrder::whereIn('id', $items)->get();
                            $suborder_items_title = $suborder_items->map(function ($item) {
                                return $item->service?->title;
                            })->implode(', ');
                            // Send WhatsApp message
                            $receiverNumber = Auth::guard('sanctum')->user()->phone;
                            $message = __('You have successfully canceled services #') .$suborder_items_title.__('  for order #').$order_id;
                            dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));

                            // Get service IDs for the given order
                            $orders_services_ids = SubOrder::whereIn('id', $items)
                                ->pluck('service_id');

                            // Get unique provider IDs
                            $service_provider_ids = Service::whereIn('id', $orders_services_ids)
                                ->whereNotNull('provider_id')
                                ->pluck('provider_id')
                                ->unique();

                            // Get unique provider emails
                            $providers = User::whereIn('id', $service_provider_ids)->get();
                            // Send order email to provider
                            if ($providers->isNotEmpty()) {
                                $message= __('You have a new order cancellation #') . $order_id;
                                foreach ($providers as $provider) {
                                    $receiverNumber = $provider?->phone;
                                    dispatch(new \Modules\WhatsAppBookingSystem\app\Jobs\SendWhatsAppMessage($receiverNumber, $message));
                                }
                            }
                        }
                    }catch(\Exception $exception){

                    }

                    $order_wise_all_suborder_is_refund = SubOrder::where('order_id', $order->id)
                    ->pluck('is_refunded')
                    ->toArray();

                    // Step 1: If all sub-order is_refund status is 1, set main order is_refund status to 1
                    if (!in_array(0, $order_wise_all_suborder_is_refund)){
                        $order->is_refunded = 1;
                    } else {
                        // otherwise 0
                        $order->is_refunded = 0;
                    }

                    $order->save();


                    $message="Order Cancel successfully";
                    return response()->json([
                        'success' => $success,
                        'message' => $message
                    ], 200);
            }
            else
            {
                return response()->json([
                    'success' => $success,
                    'message' => $message
                ], 400);
            }




        }

    }

    public function updatePaymentInfo(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => __("Unauthorized access")
            ], 401);
        }

        $refund_id=$request->refund_id;
        $gateway_id=$request->gateway_id;
        $gateway_fields=$request->gateway_fields;

        $refunded_order=RefundedOrder::where('id',$refund_id)->first();
        if($refunded_order)
        {
            $refunded_order->gateway_id=$gateway_id;
            $refunded_order->gateway_fields=$gateway_fields;
            $refunded_order->save();

            return response()->json([
               'success' => true,
               'message' => __("Payment information updated successfully")
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => __("Refunded order not found")
            ], 400);
        }
    }


}
