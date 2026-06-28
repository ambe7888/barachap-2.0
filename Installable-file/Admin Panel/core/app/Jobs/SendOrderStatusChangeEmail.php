<?php

namespace App\Jobs;

use App\Mail\BasicMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusChangeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function handle(): void
    {
        // Define status messages
        if (!empty($this->emailData['status']) && $this->emailData['status'] == 1) {
            $statusMessages = [
                4 => [ // Canceled
                    'subject' => __('Your order has been accepted'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s has been accepted.')
                ],
                5 => [ // Declined
                    'subject' => __('Your order has been accepted'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s has been accepted.')
                ],
            ];
        }elseif(!empty($this->emailData['status']) && $this->emailData['status'] == 2) {
            $statusMessages = [
                4 => [ // Canceled
                    'subject' => __('Your order complete request has been approved'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s complete request has been approved.')
                ],
                5 => [ // Declined
                    'subject' => __('Your order complete request has been approved'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s complete request has been approved.')
                ],
            ];
        }else{
            $statusMessages = [
                4 => [ // Canceled
                    'subject' => __('Your order has been cancelled'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s has been cancelled.')
                ],
                5 => [ // Declined
                    'subject' => __('Your order has been declined'),
                    'message' => __('Your order ID: %1$s and Sub Order ID: %2$s has been declined.')
                ],
            ];
        }


        // Get the status message based on the email data
        $status = $this->emailData['status'];
        $statusMessage = $statusMessages[$status] ?? null;

        // Send email to client
        if ($statusMessage) {
            try {
                Mail::to($this->emailData['client_email'])->send(new BasicMail([
                    'subject' => $statusMessage['subject'],
                    'message' => sprintf($statusMessage['message'], $this->emailData['order_id'], $this->emailData['sub_order_id']),
                ]));
            } catch (\Exception $e) {
            }

            // Send email to provider
            try {
                // Define the provider message
                $providerMessage = $status === 4
                    ? __('Your Order ID: %1$s has been cancelled.')
                    : __('Your Order ID: %1$s has been declined.');

                Mail::to($this->emailData['provider_email'])->send(new BasicMail([
                    'subject' => $statusMessage['subject'],
                    'message' => sprintf($providerMessage, $this->emailData['sub_order_id']),
                ]));
            } catch (\Exception $e) {
            }

            // Send email to admin
            try {
                $adminMessage = $status === 4
                    ? __('Sub Order ID: %1$s was cancelled by Provider ID: %2$s.')
                    : __('Sub Order ID: %1$s was declined by Provider ID: %2$s.');

                Mail::to($this->emailData['admin_email'])->send(new BasicMail([
                    'subject' => __('Order Status Update'),
                    'message' => sprintf($adminMessage,
                        $this->emailData['sub_order_id'],
                        $this->emailData['provider_id']
                    ),
                ]));
            } catch (\Exception $e) {
            }
        }

    }
}
