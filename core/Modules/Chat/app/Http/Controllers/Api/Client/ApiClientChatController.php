<?php

namespace Modules\Chat\app\Http\Controllers\Api\Client;

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

class ApiClientChatController extends Controller
{
    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

    public function provider_list()
    {
        $clientId = auth('sanctum')->id();

        $latestMessageSubquery = LiveChatMessage::select('live_chat_id',
            DB::raw('MAX(message) as message'),
            DB::raw('MAX(created_at) as created_at')
        )
            ->whereIn('live_chat_id', LiveChat::where('client_id', $clientId)->pluck('id'))
            ->groupBy('live_chat_id');

       // chat list with necessary relationships and unseen message count
        $clientChatList = LiveChat::with([
            'provider:id,first_name,last_name,image,check_online_status',
            'livechatMessage' => function ($query) {
                $query->latest(); // Only take the latest message
            }
        ])
        ->withCount(['client_unseen_msg', 'provider_unseen_msg'])
        ->where('client_id', $clientId)
            ->joinSub($latestMessageSubquery, 'latest_messages', function ($join) {
                $join->on('live_chats.id', '=', 'latest_messages.live_chat_id');
            })
            ->groupBy('live_chats.id', 'live_chats.provider_id','live_chats.client_id', 'live_chats.admin_id', 'live_chats.created_at', 'live_chats.updated_at', 'latest_messages.message', 'latest_messages.created_at') // Ensure all selected columns are in GROUP BY
            ->orderBy('latest_messages.created_at', 'desc')// Then order by latest message timestamp
            ->paginate(20)
        ->withQueryString();

        if ($clientChatList->isEmpty()) {
            return response()->json([
                'chat_list' => [],
                'active_users' => [],
                'activity_check' => [],
                'pagination' => [
                    'total' => 0,
                    'count' => 0,
                    'per_page' => 20,
                    'current_page' => 1,
                    'last_page' => 1,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ]
            ]);
        }
    

        // Pluck provider IDs from the chat list and check if they are active
        $providerIds = $clientChatList->pluck('provider_id')->toArray();
        $activeUsers = array_filter($providerIds, function ($id) {
            return Cache::has('user_is_online_' . $id);
        });

        // Check the provider activity status (when they were last seen online)
        $activityCheck = $clientChatList->mapWithKeys(function ($chat) {
            $provider = $chat->provider;
            if(!$provider)
            {
              return [];
            }  
            return [
                $provider->id => optional($provider->check_online_status)->diffForHumans(),
            ];
        });


        return response()->json([
            'chat_list' => ClientChatListResource::collection($clientChatList),
            'active_users' => $activeUsers ? ChatActiveUserListResource::collection($activeUsers) :null,
            'activity_check' => $activityCheck,
            'pagination' => [
                'total' => $clientChatList->total(),
                'count' => $clientChatList->count(),
                'per_page' => $clientChatList->perPage(),
                'current_page' => $clientChatList->currentPage(),
                'last_page' => $clientChatList->lastPage(),
                'next_page_url' => $clientChatList->nextPageUrl(),
                'prev_page_url' => $clientChatList->previousPageUrl(),
            ]
        ]);
    }


    public function fetch_record($provider_id)
    {

        if(!Auth::guard('sanctum')->user()){
            return response()->json(['error' => __('You are not authorized to perform this action.')], 403);
        }

        // check user type
       $find_provider =  User::where('id', $provider_id)->where('type', 0)->first();
        if(empty($find_provider)){
            return response()->json(['message' => __('Provider not found')])->setStatusCode('422');
        }

        // Fetch live chat and chat messages
        $client_id = Auth::guard('sanctum')->user()->id;
        $live_chat = LiveChat::where('client_id', $client_id)
            ->where('provider_id', $provider_id)
            ->first();

        if (empty($live_chat)){
            return response()->json([
                'all_message' => [],
                'message' => __('Message Not Found'),
            ]);
        }

        $all_message = LiveChatMessage::where('live_chat_id', $live_chat->id)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        LiveChatMessage::where('from_user',2)
            ->where('live_chat_id', $live_chat->id)
            ->update(['is_seen'=>1]);

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

    public function message_send(Request $request)
    {

            //: send message
            $find_provider = User::where('id',$request->provider_id)->where('type', 0)->first();
            if(empty($find_provider)){
                return response()->json(['msg'=> __('User not found')])->setStatusCode('422');
            }

            $message_send = UserChatService::send(
                auth('sanctum')->id(),
                (int)$request->provider_id,
                $request->message,1,
                $request->file,
                (int) ($request->service_id ?? $request->job_id),
                $request->type,
                $request->job_offer_id ?? '',
                $request->interview_message ?? '',
            );


            try {
                  $provider = User::select('id', 'email', 'check_online_status', 'firebase_token')
                        ->where('id', $request->provider_id)
                        ->first();

                    if ($provider && $provider->firebase_token) {
                        $title = __('New Message from :name', ['name' => auth('sanctum')->user()->fullname]);
                        $body = $request->message;

                        $file = null;
                        if (!empty($request->file) && $request->hasFile('file')){
                           $file = asset('assets/uploads/media-uploader/live-chat/'.$message_send['message']['file'] ?? '');
                        }

                        $user = Auth::guard('sanctum')->user();

                        $provider_notification_data = [
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
                        $this->orderServiceNotification->sendFirebaseNotification([$provider->firebase_token], $title, $body, $provider_notification_data);
                }
            }catch (\Exception $exception){
            }


            if($request->from === 'chatbox'){
                return $message_send;
            }

        return response()->json([
            'status'=>'Message successfully send',
            'message' =>  new LiveChatSingleMessageResource($message_send['message']),
        ]);
    }


    public function credentials()
    {

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Pusher app credentials
        $pusher_app_id = !empty(env('PUSHER_APP_ID')) ? env('PUSHER_APP_ID') : '';
        $pusher_app_key = !empty(env('PUSHER_APP_KEY')) ? env('PUSHER_APP_KEY') : '';
        $pusher_app_secret = !empty(env('PUSHER_APP_SECRET')) ? env('PUSHER_APP_SECRET') : '';
        $pusher_app_cluster = !empty(env('PUSHER_APP_CLUSTER')) ? env('PUSHER_APP_CLUSTER') : '';

        return response()->json([
            'pusher_app_id' => $pusher_app_id,
            'pusher_app_key' => $pusher_app_key,
            'pusher_app_secret' => $pusher_app_secret,
            'pusher_app_cluster' => $pusher_app_cluster,
        ]);
    }

    //unseen message count
    public function unseen_message_count()
    {

        $client_id = auth('sanctum')->user()->id;

        $message = User::select('id')->withCount(['client_unseen_message' => function($q){
            $q->where('is_seen',0)->where('from_user',2);
        }])->where('id', $client_id)->first();

        $unread_notifications = UserNotification::where('user_id', $client_id)->where('is_read', 'unread')->count();

        return response()->json([
            'unseen_message' => $message,
            'unread_notifications' => $unread_notifications,
        ]);
    }
}
