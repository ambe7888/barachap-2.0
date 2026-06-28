<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Helpers\FlashMsg;


class ProviderOfferController extends Controller
{
    public function sendOfferNotification(Request $request)
    {
        // Retrieve all client users
        $providers = User::where('type', 0)->get();
        //$clients = User::all();
        if ($providers->isEmpty()) {
            return redirect()->back()->with(FlashMsg::item_new(__('No provider found.')));
        }
        // Prepare notification details
        $offer_id=$request->input("offer_id");
        $providerTitle = $request->input("offer_title");
        $providerBody = $request->input("offer_subtitle");
        $providerNotificationData = [
            "title" => $providerTitle,
            "detailed_title" => "-",
            "identify" => $offer_id,
            "body" => $providerBody,
            "description" => "-",
            "type" => "offer",
            "sound" => "default",
            "screen" => "-",
        ];
        // Save notifications for each client
        foreach ($providers as $provider) {
            $id = $provider->id;
            user_notification($offer_id, $id, 'offer', $providerTitle, 'unread');
        }
        // Send Firebase notification
        $this->sendFirebaseNotification($providerTitle, $providerBody, $providerNotificationData);
        return redirect()->back()->with(FlashMsg::item_new(__('Send notification successfully')));
    }
    private function sendFirebaseNotification($title, $body, $data)
    {
        try {
            /// Check if the third parameter (image URL) is being passed as an array.
            $imageUrl = isset($data['imageUrl']) && is_string($data['imageUrl']) ? $data['imageUrl'] : null;
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
            // Create the Notification object
            $notification = Notification::create($title, $body, $imageUrl);
            
            $data = array_map(function ($value) {
                return is_array($value) ? json_encode($value) : (string)$value;
            }, $data);
            
            // Construct the CloudMessage with notification and data payloads
            $message = CloudMessage::fromArray([
                'topic' => 'provider',
                'data' => $data,
            ]);
            // Send the notification to multiple tokens
            $messaging->send($message);
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_new(__('Failed to send Firebase notification: ') . $e->getMessage()));
        }
    }
}
