<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\Service;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Single_OrderServiceNotification
{
    public function createOrderNotification($last_order_id, $request)
    {
        // Fetch the order details along with related sub-orders
        $order_details = Order::with('client', 'subOrders.subOrderAddons', 'subOrders.subOrderLocations', 'subOrders.staff', 'subOrders.provider')
            ->find($last_order_id);

        if (!$order_details) {
            return; // Order not found, exit the function
        }

        // Check the main order user's type and create a notification if the user type is 1
        $main_user = $order_details->client;
        if ($main_user && $main_user->type === 1) {
            $this->sendFirebaseNotification($main_user->firebase_token, __('Order Created'), __('Your order has been created successfully.'));
            $this->sendUserNotification($last_order_id, $main_user->id, __('Your order has been placed successfully.'));
        }


        // Track notified admins to prevent duplicate notifications
        $notified_admins = [];

        // Create notifications for each provider associated with the sub-orders
        foreach ($order_details->subOrders as $subOrder) {
            // If the sub-order has an associated admin, send a notification to the admin
            if (!empty($subOrder->admin_id) && !in_array($subOrder->admin_id, $notified_admins)) {
                $this->sendAdminNotification($last_order_id, $subOrder->admin_id, __('You have a new order.'));
                $notified_admins[] = $subOrder->admin_id; // Mark this admin as notified
            }else{

                if($subOrder->provider){
                    $this->sendFirebaseNotification($subOrder->provider->firebase_token, __('Order Created'), __('Your order has been created successfully.'));
                }

                $provider_id = $subOrder->provider_id;
                $this->sendUserNotification($last_order_id, $provider_id, __('You have a new order.'));
            }
        }

    }

    private function sendUserNotification($order_id, $user_id, $message)
    {
        user_notification($order_id, $user_id, 'order', $message, 'unread');
    }

    private function sendAdminNotification($order_id, $admin_id, $message)
    {
        admin_notification($order_id, $admin_id, 'order', $message, 'unread');
    }

    private function sendFirebaseNotification(array $firebaseToken, $title, $body)
    {
        // Path to the Firebase credentials JSON file
        $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

        // Load the credentials from the JSON file
        $jsonCredentials = file_get_contents($credentialsPath);
        $credentials = json_decode($jsonCredentials, true);

        // Convert to JSON
        $jsonCredentials = json_encode($credentials);

        // Initialize Firebase Admin SDK
        $factory = (new Factory)->withServiceAccount($jsonCredentials);
        $messaging = $factory->createMessaging();


        // Construct the message
        $message = CloudMessage::withTarget('token', $firebaseToken)
            ->withNotification(Notification::create($title, $body))
            ->withData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // Add any other data here
            ]);

        try {
            // Send the notification
            $messaging->send($message);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        }
    }

}
