<?php

namespace Modules\Chat\app\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Chat\app\Models\LiveChat;
use Modules\Chat\app\Models\LiveChatMessage;
use Modules\Chat\app\Services\UserChatService;
use Modules\Chat\app\Http\Requests\FetchChatRecordRequest;
use App\Actions\Services\ImageModifier;
use App\Http\Services\OrderServiceNotification;
use App\Models\User;

class ProviderChatController extends Controller
{
    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }
    public function live_chat()
    {

        $providerId = Auth::user()->id;;

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


         $arr = "";
        foreach($provider_chat_list->pluck("client_id") as $id){
            $arr .= "client_id_". $id .": false,";
        }

        $arr = rtrim($arr,",");
        return view("chat::provider.index",compact('provider_chat_list','arr'));

    }


    public function fetch_chat_record(FetchChatRecordRequest $request){
        $data = $request->validated();
        $data = UserChatService::fetch($data["provider_id"],$data["client_id"],from: 2);

        $body = view("chat::provider.message-body", compact('data'))->render();
        $header = view("chat::provider.message-header", compact('data'))->render();

        return response()->json([
            "body" => $body,
            "header" => $header,
            "allow_load_more" => $data->allow_load_more ?? false,
        ]);
    }


     public function message_send(Request $request)
    {

        if(empty($request->message) && empty($request->file)){
            $request->validate([
                'message'=>'required',
                'client_id'=>'required',
            ],
            [
                'message.required'=>'Any message needed'
                
            ]);
        }

        if(!empty($request->file)){
            $request->validate([
                'file'=>'required|mimes:jpg,png,jpeg,gif,webp,pdf',
                'client_id'=>'required',
            ],[
                'file.required' => 'File required',
                'file.mimes' => "File Extension should be jpg,png,jpeg,gif,webp,pdf"
            ]);
        }

        // send message
        $message_send = UserChatService::send(
            (int)$request->client_id,
            Auth::user()->id,
            $request->message,2,
            $request->file,
            $request->service_id ?? null);

        try {
            if (!empty($request->client_id)){
                $client = User::select('id', 'email', 'check_online_status', 'firebase_token')
                    ->where('id', $request->client_id)
                    ->first();

                if ($client && $client->firebase_token) {
                    $title = __('New Message from :name', ['name' => Auth::user()->fullname]);
                    $body = $request->message;

                    $file = null;
                    if (!empty($request->file) && $request->hasFile('file')){
                        $file = $message_send['message']['file'] ?? '';
                    }

                    $user = Auth::user();

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

        if($request->from === 'chatbox'){
            return $message_send;
        }

        return redirect()->route('provider.live.chat',[
            'client_id'=>$request->client_id
        ]);
    }








}
