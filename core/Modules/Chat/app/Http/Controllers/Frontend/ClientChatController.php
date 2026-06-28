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
use App\Models\User;
use Modules\Chat\app\Services\UserChatService;
use Modules\Chat\app\Http\Requests\FetchChatRecordRequest;
use App\Actions\Services\ImageModifier;
use App\Http\Services\OrderServiceNotification;



class ClientChatController extends Controller
{

    protected $orderServiceNotification;

    public function __construct(OrderServiceNotification $orderServiceNotification)
    {
        $this->orderServiceNotification = $orderServiceNotification;
    }

     public function live_chat()
    {

        $clientChatList = LiveChat::with("client","provider")
        ->whereHas('provider')
        ->withCount("client_unseen_msg","provider_unseen_msg")
        ->where("client_id", auth("web")->id())
        ->orderByDesc('client_unseen_msg_count')
        ->get();
       

         $arr = "";
        foreach($clientChatList->pluck("provider_id") as $id){
            $arr .= "provider_id_". $id .": false,";
        }
        $arr = rtrim($arr,",");

       return view("chat::client.index",compact('clientChatList','arr'));

    }


    public function fetch_chat_record(FetchChatRecordRequest $request){
       
        $data = $request->validated();
        $data = UserChatService::fetch($data["provider_id"],$data["client_id"],from: 1);
      

        $body = view("chat::client.message-body", compact('data'))->render();
        $header = view("chat::client.message-header", compact('data'))->render();

        return response()->json([
            "body" => $body,
            "header" => $header,
            "allow_load_more" => $data->allow_load_more ?? false,
        ]);
    }



       public function message_send(Request $request)
    {

        if(!empty($request->file)){
            $request->validate([
                'file'=>'required|mimes:jpg,png,jpeg,gif,webp,pdf',
                'provider_id'=>'required',
            ],[
                'file.required' => 'File required',
                'file.mimes' => "File Extension should be jpg,png,jpeg,gif,webp,pdf"
            ]);
        }
            //: send message
            $message_send = UserChatService::send(
                Auth::user()->id,
                (int)$request->provider_id,
                $request->message,1,
                $request->file,
                (int) ($request->service_id ?? $request->job_id),
                $request->type,
                $request->job_offer_id ?? '',
                $request->interview_message ?? '',
            );


            try {
                  if(!empty($message_send['message']))
                  {
                    $provider = User::select('id', 'email', 'check_online_status', 'firebase_token')
                        ->where('id', $request->provider_id)
                        ->first();

                    if ($provider && $provider->firebase_token) {
                        $title = __('New Message from :name', ['name' => Auth::user()->fullname]);
                        $body = $request->message;

                        $file = null;
                        if (!empty($request->file) && $request->hasFile('file')){
                           $file = $message_send['message']['file'] ?? '';
                        }

                        $user = Auth::user();

                        $provider_notification_data = [
                            "id" => $message_send['message']['id'] ?? null,
                            "user_id" => Auth::user()->id ?? null,
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
                }
            }catch (\Exception $exception){
                
                return back()->with(toastr_warning($exception->getMessage()));
            }

            if($request->from === 'chatbox'){
                return $message_send;
            }

            return redirect()->route('client.live.chat');

       
    }




}
