<?php

namespace Modules\Chat\app\Http\Controllers\Api\Provider;

use App\Actions\Services\ImageModifier;
use App\Http\Controllers\Controller;
use App\Http\Services\OrderServiceNotification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Chat\app\Models\LiveChat;
use Modules\Chat\app\Models\LiveChatMessage;
use Modules\Chat\app\Resources\ChatActiveUserListResource;
use Modules\Chat\app\Resources\ClientChatListResource;
use Modules\Chat\app\Resources\LiveChatMessageListsResource;
use Modules\Chat\app\Resources\LiveChatSingleMessageResource;
use Modules\Chat\app\Services\UserChatService;

class ApiProviderChatController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function client_list()
    {

        $providerId = auth('sanctum')->id();

        // Subquery to get the latest message for each live chat
        $latestMessageSubquery = LiveChatMessage::select('live_chat_id',
            DB::raw('MAX(message) as message'), // Use MAX to get the latest message
            DB::raw('MAX(created_at) as created_at') // Use MAX to get the latest created_at timestamp
        )
            ->whereIn('live_chat_id', LiveChat::where('provider_id', $providerId)->pluck('id'))
            ->groupBy('live_chat_id'); // Group by live_chat_id to avoid duplicate entries

        $provider_chat_list = LiveChat::with([
            'client:id,first_name,last_name,image,check_online_status',
            'livechatMessage' => function ($query) {
                $query->latest(); // Only take the latest message
            }
        ])
        ->withCount(['client_unseen_msg', 'provider_unseen_msg'])
        ->where('provider_id', $providerId)
        ->joinSub($latestMessageSubquery, 'latest_messages', function ($join) {
            $join->on('live_chats.id', '=', 'latest_messages.live_chat_id');
        })
        ->groupBy('live_chats.id', 'live_chats.client_id','live_chats.provider_id', 'live_chats.admin_id', 'live_chats.created_at', 'live_chats.updated_at', 'latest_messages.message', 'latest_messages.created_at') // Ensure all selected columns are in GROUP BY
        ->orderBy('latest_messages.created_at', 'desc') // Then order by latest message timestamp
        ->paginate(20)
        ->withQueryString();

        // Check which users are currently active (online)
        $active_users = $provider_chat_list->pluck('client')->filter(function ($client) {
            return $client && Cache::has('user_is_online_' . $client->id);
        });

        // Check last activity status of users
        $activity_check = $provider_chat_list->mapWithKeys(function ($list) {
            return [$list->client?->id => optional($list->client?->check_online_status)->diffForHumans()];
        });

        return response()->json([
            'chat_list' => ClientChatListResource::collection($provider_chat_list),
            'active_users' => $active_users->isNotEmpty() ? ChatActiveUserListResource::collection($active_users) : null,
            'activity_check' => $activity_check,
            'pagination' => [
                'total' => $provider_chat_list->total(),
                'count' => $provider_chat_list->count(),
                'per_page' => $provider_chat_list->perPage(),
                'current_page' => $provider_chat_list->currentPage(),
                'last_page' => $provider_chat_list->lastPage(),
                'next_page_url' => $provider_chat_list->nextPageUrl(),
                'prev_page_url' => $provider_chat_list->previousPageUrl(),
            ]
        ]);

    }

    public function fetch_record($client_id)
    {

        if(!Auth::guard('sanctum')->user()){
            return response()->json(['error' => __('You are not authorized to perform this action.')], 403);
        }

        // check user type
        $find_client =  User::where('id', $client_id)->where('type', 1)->first();
        if(empty($find_client)){
            return response()->json(['message' => __('Client not found')])->setStatusCode('422');
        }

        // Fetch live chat and chat messages
        $provider_id = Auth::guard('sanctum')->user()->id;
        $live_chat = LiveChat::where('client_id', $client_id)->where('provider_id', $provider_id)->first();

        if (empty($live_chat)){
            return response()->json([
                'all_message' => [],
                'message' => __('Message Not Found'),
            ]);
        }

        $all_message = LiveChatMessage::where('live_chat_id', $live_chat->id)->latest()->paginate(20)->withQueryString();
        LiveChatMessage::where('from_user', 1)->where('live_chat_id', $live_chat->id)->update(['is_seen'=>1]);

        if($all_message){
            return response()->json([
                'all_message' => LiveChatMessageListsResource::collection($all_message),
                'pagination' => [
                    'total' => $all_message->total(),
                    'count' => $all_message->count(),
                    'per_page' => $all_message->perPage(),
                    'current_page' => $all_message->currentPage(),
                    'last_page' => $all_message->lastPage(),
                    'next_page_url' => $all_message->nextPageUrl(),
                    'prev_page_url' => $all_message->previousPageUrl(),
                ]
            ]);
        }

        return response()->json(['msg' => __('No message found.')]);
    }

    public function message_send(Request $request)
    {

        if(empty($request->message) && empty($request->file)){
            $request->validate([
                'message'=>'required',
                'client_id'=>'required',
            ]);
        }

        if(!empty($request->file)){
            $request->validate([
                'file'=>'required|mimes:jpg,png,jpeg,gif,webp,pdf',
                'client_id'=>'required',
            ]);
        }

        // send message
        $message_send = UserChatService::send(
            (int)$request->client_id,
            auth('sanctum')->id(),
            $request->message,2,
            $request->file,
  $request->service_id ?? null);

        try {
            if (!empty($request->client_id)){
                $client = User::select('id', 'email', 'check_online_status', 'firebase_token')
                    ->where('id', $request->client_id)
                    ->first();

                if ($client && $client->firebase_token) {
                    $title = __('New Message from :name', ['name' => auth('sanctum')->user()->fullname]);
                    $body = $request->message;

                    $file = null;
                    if (!empty($request->file) && $request->hasFile('file')){
                        $file = asset('assets/uploads/media-uploader/live-chat/'.$message_send['message']['file'] ?? '');
                    }

                    $user = Auth::guard('sanctum')->user();

                    $client_notification_data = [
                        "id" => $message_send['message']['id'] ?? null,
                        "user_id" => auth('sanctum')->id() ?? null,
                        "user_name" => $user->fullname ?? null,
                        "user_image" => ImageModifier::ImageUrl($user->image) ?? null,
                        "live_chat_id" => $message_send['message']['live_chat_id'] ?? null,
                        "from_user" => $message_send['message']['from_user'] ?? null,
                        "message_title" => $title,
                        "message" => $body,
                        "file" => $file,
                        "is_seen" => $message_send['message']['is_seen'],
                        "type" => "chat",
                        "updated_at" => $message_send['message']['updated_at'] ?? null,
                        "created_at" => $message_send['message']['created_at'] ?? null,
                    ];

                    // Pass the token as an array
                    $this->orderServiceNotification->sendFirebaseNotification([$client->firebase_token], $title, $body, $client_notification_data);
                }
            }
        }catch (\Exception $exception){}

        return response()->json([
            'status'=> __('Message successfully send'),
            'message' => new LiveChatSingleMessageResource($message_send['message']) ?? '',
        ]);
    }


    //unseen message count
    public function unseen_message_count()
    {
        $provider_id = auth('sanctum')->user()->id;
        $message = User::select('id')->withCount(['provider_unseen_message' => function($q){
            $q->where('is_seen',0)->where('from_user',1);
        }])->where('id', $provider_id)->first();

        $unread_notifications = UserNotification::where('user_id', $provider_id)->where('is_read', 'unread')->count();

        return response()->json([
            'unseen_message' => $message,
            'unread_notifications' => $unread_notifications,
        ]);
    }
}
