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

class SendRedundRequestEmail implements ShouldQueue
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
       $suborder_items=implode(',', $this->items);
       
        $mail_subject = get_static_option('refund_order_email_subject') ?? __("Refund Request for sub order  #") . $suborder_items;
        $message_for_provider_admin = get_static_option('refund_order_admin_message') ?? __("Refund Request for Sub order:  #") . $suborder_items;
        $message_for_client = get_static_option('refund_order_client_message') ?? __("You have successfully send an refund request for #") . $suborder_items;
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
