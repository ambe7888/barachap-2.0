<?php

namespace App\Jobs;

use App\Mail\OrderMail;
use App\Models\Backend\Admin;
use App\Models\Service;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order_details;
    public $items=[];

    public function __construct($order_details,$items)
    {
        $this->order_details = $order_details;
        $this->items = $items;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order_details = $this->order_details;

        
        // Get service IDs for the given order
        $orders_services_ids = SubOrder::whereIn('id', $this->items)
            ->pluck('service_id');

        // Get unique provider IDs
        $service_provider_ids = Service::whereIn('id', $orders_services_ids)
            ->whereNotNull('provider_id')
            ->pluck('provider_id')
            ->unique();

        // Get unique provider emails
        $providers = User::whereIn('id', $service_provider_ids)->get();


        // Get unique client email
        $client = User::select('id', 'email')->where('id', $order_details->user_id)->first();

        // Email details
        //write message where suborder item title also included in the message
        $suborder_items = SubOrder::where('order_id', $order_details->id)->get();
        $suborder_items_title = $suborder_items->map(function ($item) {
            return $item->title;
        })->implode(', ');
        $suborder_items_title = strip_tags($suborder_items_title);
       
        $mail_subject = get_static_option('order_cancel_email_subject') ?? __("Order Cancel #") . $suborder_items_title;
        $message_for_provider_admin = get_static_option('order_cancel_admin_message') ?? __("One Order Cancel #") . $suborder_items_title;
        $message_for_client = get_static_option('order_cancel_client_message') ?? __("You have successfully canceled an order #") . $suborder_items_title;
        $global_email = get_static_option('site_global_email');

       

        // Send order email to provider
        if ($providers->isNotEmpty()) {
            foreach ($providers as $provider) {
                try {
                    Mail::to($provider->email)->send(new OrderMail(strip_tags($mail_subject) . $order_details->id, $order_details,strip_tags($message_for_provider_admin) . $order_details->id, $provider->type));
                } catch (\Exception $e) {
                }
            }
        }

        // Send admin global email
        try {
            Mail::to($global_email)->send(new OrderMail(strip_tags($mail_subject) . $order_details->id, $order_details, strip_tags($message_for_provider_admin) . $order_details->id, 1));
        } catch (\Exception $e) {
        }


        // Send order email to client
        if ($client) {
            try {
                Mail::to($client->email)->send(new OrderMail(strip_tags($mail_subject) . $order_details->id, $order_details, $message_for_client, $client->type));
            } catch (\Exception $e) {
                 
            }
        }
    }
}
