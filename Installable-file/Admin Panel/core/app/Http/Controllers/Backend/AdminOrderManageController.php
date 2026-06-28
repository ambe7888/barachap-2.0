<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Backend\AdminNotification;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\SubOrderAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminOrderManageController extends Controller
{
    public function allUserOrders(Request $request){

        $status = $request->input('status', 'all');

        $query = Order::with([
            'client',
            'subOrders.subOrderAddons',
            'subOrders.subOrderLocations',
            'subOrders.staff'
        ])->whereHas('subOrders', function ($subQuery) {
             $subQuery->where('admin_id', null);
        });

        // filter with status
        if($status !== 'all' && $status !== null){
            $query->where('status', (int)$status);
        }

        if ($status === 'job_order') {
            $query->whereHas('subOrders', function ($subOrderQuery) {
                $subOrderQuery->whereNotNull('job_post_id')->whereNull('service_id');
           });
        }elseif ($status === 'service_order') {
            // Filter for service orders only
            $query->whereHas('subOrders', function ($subOrderQuery) {
                $subOrderQuery->whereNotNull('service_id')->whereNull('job_post_id');
           });
        }

        $all_orders = $query->latest()->paginate(10);

        $orderCounts = Order::selectRaw('
                COUNT(DISTINCT CASE WHEN sub_orders.service_id IS NOT NULL THEN orders.id END) as total_service_orders,
                COUNT(DISTINCT CASE WHEN sub_orders.job_post_id IS NOT NULL THEN orders.id END) as total_job_orders,
                COUNT(DISTINCT CASE WHEN sub_orders.service_id IS NOT NULL AND orders.status = "0" THEN orders.id END) as service_pending_order,
                COUNT(DISTINCT CASE WHEN sub_orders.service_id IS NOT NULL AND orders.status = "1" THEN orders.id END) as service_active_order,
                COUNT(DISTINCT CASE WHEN sub_orders.service_id IS NOT NULL AND orders.status = "2" THEN orders.id END) as service_completed_order,
                COUNT(DISTINCT CASE WHEN sub_orders.service_id IS NOT NULL AND orders.status = "4" THEN orders.id END) as service_cancelled_order,
                COUNT(DISTINCT CASE WHEN sub_orders.job_post_id IS NOT NULL AND orders.status = "0" THEN orders.id END) as job_pending_order,
                COUNT(DISTINCT CASE WHEN sub_orders.job_post_id IS NOT NULL AND orders.status = "1" THEN orders.id END) as job_active_order,
                COUNT(DISTINCT CASE WHEN sub_orders.job_post_id IS NOT NULL AND orders.status = "2" THEN orders.id END) as job_completed_order,
                COUNT(DISTINCT CASE WHEN sub_orders.job_post_id IS NOT NULL AND orders.status = "4" THEN orders.id END) as job_cancelled_order
            ')
            ->leftJoin('sub_orders', 'orders.id', '=', 'sub_orders.order_id')
            ->whereNull('sub_orders.admin_id')
            ->first();

               $total_pending_order = Order::where('status', 0)->count();
               $total_completed_order = Order::where('status', 1)->count();

        return view('backend.pages.orders.user-orders.all_orders', [
            'all_orders' => $all_orders,
            'total_service_orders' => $orderCounts->total_service_orders,
            'total_job_orders' => $orderCounts->total_job_orders,
            'service_pending_order' => $orderCounts->service_pending_order,
            'service_active_order' => $orderCounts->service_active_order,
            'total_pending_order' =>$total_pending_order,
            'total_completed_order' =>$total_completed_order,
            'service_cancelled_order' => $orderCounts->service_cancelled_order,
            'job_pending_order' => $orderCounts->job_pending_order,
            'job_active_order' => $orderCounts->job_active_order,
            'job_completed_order' => $orderCounts->job_completed_order,
            'job_cancelled_order' => $orderCounts->job_cancelled_order,
            'current_status' => $status,
        ]);
    }

    public function orderDetails($id,$notificationId = null){
        
        $order = Order::with('client','subOrders.subOrderAddons','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.service', 'subOrders.job')->find($id);
        if (!$order) {
            
            try {
                AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
            }catch (\Exception $exception){}

            abort(404);
        }

        try {
            AdminNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        }catch (\Exception $exception){}

        return view('backend.pages.orders.order-details', compact('order'));
    }

    public function subOrderAddonDetails($id){

        $suborder = SubOrder::with(['subOrderAddons', 'subOrderLocations.state', 'subOrderLocations.city', 'subOrderLocations.area'])->find($id);

        if (!$suborder) {
            abort(404);
        }
        return view('backend.pages.orders.suborders.sub-order-addon-details',
            [
             'suborder' => $suborder,
            ]
        );
    }


    public function changeStatus(Request $request){
        $order_id = $request->id;
        $status = $request->status_id;
        $order = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.provider', 'subOrders.staff')
            ->where('id',$order_id)
            ->first();

        if (!$order) {
            abort(404);
        }

        $current_status = $order->status;
        $old_status = '';
        $pending = __('Pending');
        $active = __('Active');
        $completed = __('Completed');
        $cancel = __('Cancel');

        // old order status
        if ($current_status == 0){
            $old_status = $pending;
        }elseif($current_status == 1){
            $old_status = $active;
        }elseif ($current_status == 2){
            $old_status = $completed;
        }elseif ($current_status == 3){
            $old_status = $cancel;
        }

        // new order status
        if($order->status==0){
            $new_status = 'Active';
        }elseif($order->status==1){
            $new_status = 'Completed';
        }else{
            $new_status = 'Cancel';
        }

        $client_email = optional($order->client)->email;
        // update
        $order->update(['status' => $status]);

        // if order status change mail send to client and provider
        try {
            $order_status_change_title = __('Order Status Changed.') . $order->id;
            $message_status = __('Order Status Changed.'). ' ' . __('Order ID:') .$order->id;
            $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$order->name,$old_status,$new_status,$order->id],$message_status);
            Mail::to($client_email)->send(new BasicMail([
                'subject' => $order_status_change_title,
                'message' => $message
            ]));

            // sent to order provider mail multiple provider
            foreach ($order->subOrders as $provider){
                $provider_email = $provider->provider?->email;
                $provider_name = $provider->provider?->fullname;

                $message = str_replace(["@name","@old_status","@new_status","@order_id"],[$provider_name,$old_status,$new_status,$order->id],$message_status);
                Mail::to($provider_email)->send(new BasicMail([
                    'subject' => $order_status_change_title,
                    'message' => $message
                ]));
            }

        } catch (\Exception $e) { }


        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }


    // search category
    public function searchOrder(Request $request)
    {

        $searchString = strip_tags($request->string_search);

        $all_orders = Order::with([
            'client',
            'subOrders.subOrderAddons',
            'subOrders.subOrderLocations',
            'subOrders.staff'
        ])->where(function ($query) use ($searchString) {
                // Search conditions
                $query->where('total', 'LIKE', "%{$searchString}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$searchString}%");
            })
       ->where(function ($subQuery) {
            $subQuery->doesntHave('subOrders')
                ->orWhereHas('subOrders', function ($subQuery) {
                    $subQuery->where('admin_id', null);
            });
       })
        ->latest()
        ->paginate(10);

        return $all_orders->total() >= 1 ? view('backend.pages.orders.user-orders.search-order',
            compact('all_orders'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
  public function paginate(Request $request)
    {

        if($request->ajax()){

            $query = Order::with('client','subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff');
            $status = $request->query('status', 'all');

            if ($status == 'service_order') {
                $query->whereHas('subOrders', function ($subOrderQuery) {
                    $subOrderQuery->whereNotNull('service_id')->whereNull('job_post_id');
                });
            }elseif ($status == 'job_order') {
                $query->whereHas('subOrders', function ($subOrderQuery) {
                    $subOrderQuery->whereNotNull('job_post_id')->whereNull('service_id');
                });
            }elseif ($status ==  '0') {
                $query->where('status', 0);
            }elseif ($status ==  '2') {
                $query->where('status', 2);
            }elseif ($status ==  '4') {
                $query->where('status', 4);
            }

            $all_orders = $query->where(function ($subQuery) {
                        $subQuery->doesntHave('subOrders')
                            ->orWhereHas('subOrders', function ($subQuery) {
                                $subQuery->where('admin_id', null);
                            });
                    })->latest()
                    ->paginate(10);

            return view('backend.pages.orders.user-orders.search-order', compact('all_orders'))->render();
        }
    }

}
