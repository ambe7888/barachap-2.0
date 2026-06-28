<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\UserNotification;
use Core\Request\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function userAllNotification(){

        $user_all_notification = UserNotification::where('user_id', Auth::guard('sanctum')->user()->id)
          ->latest()
          ->paginate(20);

        UserNotification::where('user_id', Auth::guard('sanctum')->user()->id)
            ->where('is_read', 'unread')
            ->update(['is_read' => 'read']);

        if ($user_all_notification->isNotEmpty()) {
            return response()->json([
                'user_all_notification' => NotificationResource::collection($user_all_notification->items()),
                'pagination' => [
                    'total' => $user_all_notification->total(),
                    'count' => $user_all_notification->count(),
                    'per_page' => $user_all_notification->perPage(),
                    'current_page' => $user_all_notification->currentPage(),
                    'last_page' => $user_all_notification->lastPage(),
                    'next_page_url' => $user_all_notification->nextPageUrl(),
                    'prev_page_url' => $user_all_notification->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Notification Not Available'),
        ]);

    }

    public function allClearMessage()
    {
        $user_id = Auth::guard('sanctum')->user()->id;

        if (!$user_id) {
            return response()->json([
                'message' => __('Please Login'),
            ], 401);
        }

        UserNotification::where('user_id', $user_id)->update([
            'is_read' => 'read',
        ]);

        return response()->json([
            'message' => __('Clear all Notifications Success'),
        ]);

    }

    public function readNotificationSingle($id = null){

        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'message' => __('Please login to continue.'),
            ], 401);
        }

        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'message' => __('Notification not found.'),
            ], 404);
        }

        $notification->update(['is_read' => 'read']);

        return response()->json([
            'message' => __('Notification marked as read successfully.'),
        ]);
    }
}
