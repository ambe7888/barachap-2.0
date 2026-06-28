<?php

namespace Modules\Chat\app\Services;

use App\Models\Service;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;
use Modules\Chat\app\Events\LivechatUserMessageEvent;
use Modules\Chat\app\Events\LivechatVendorMessageEvent;
use Modules\Chat\app\Models\LiveChat;
use Modules\Chat\app\Models\LiveChatMessage;
use Modules\JobPost\app\Models\JobPost;
use Modules\JobPost\app\Models\JobPostOffer;

class UserChatService
{
    private $liveChat = null;
    private $livechatQuery = null;
    private $lastMessage = null;
    private $projectData = null;
    private $clientData = null;
    private array $message = [];
    private string $filename = '';
    private $instance;
    private array $allowedFilesExtension = ['jpeg','jpg','png','pdf','gif', 'webp'];
    public const FOLDER_PATH = 'assets/uploads/media-uploader/live-chat/';

    private static function init(): UserChatService
    {
        $init = new self();

        if(is_null($init->instance)){
            $init->instance = $init;
        }

        return $init;
    }

    //  check if this user have already charted with vendor or not
    //  if user already made any chat with vendor then display old data and if not then create new record
    private function recordIsExistsOrNot($clientId, $providerId){
        $this->livechatQuery = LiveChat::where("client_id", $clientId)->where("provider_id",$providerId);
        return $this->livechatQuery->count();
    }

    //  this method will return instance of livechat table
    //  check if record is already in database then return that and if not then create new one and return this instance
    private function livechatInstance(int $clientId, int $providerId){
        if($this->recordIsExistsOrNot($clientId, $providerId) > 0){
            return $this->livechatQuery->first();
        }

        return LiveChat::create([
            "client_id" => $clientId,
            "provider_id" => $providerId
        ]);
    }

    //  create new livechatmessage record
    private function sendMessage(array|object $data){
        $this->lastMessage = LiveChatMessage::create($data);

        return $this->lastMessage;
    }

    private function fetch_provider_info($provider_id){
        if(gettype($provider_id) == 'integer'){
            $this->userData = User::select("id","image","first_name","last_name","username")->find($provider_id);
            return $this->userData;
        }

        if(is_null($provider_id)){
            return null;
        }

        //  now throw exception
        throw new InvalidArgumentException("Invalid freelancer id this id should be integer " . gettype($provider_id) . ' given at line:'. __LINE__ . " File: ". __FILE__);
    }

    private function fetch_client_info(?int $client_id)
    {
        if(gettype($client_id) == 'integer'){
            $this->clientData = User::select("id","image","first_name","last_name","username")->find($client_id);
            return $this->clientData;
        }

        if(is_null($client_id)){
            return null;
        }

        //  now throw exception
        throw new InvalidArgumentException("Invalid client id this id should be integer " . gettype($client_id) . ' given at line:'. __LINE__ . " File: ". __FILE__);
    }

    private function updateUnSeen($livechat_id, $type): void
    {
        LiveChatMessage::where("live_chat_id", $livechat_id)
            ->when($type == 1, function ($query) {
                $query->where("from_user", 2);
            })->when($type == 2, function ($query) {
                $query->where("from_user", 1);
            })->update([
                "is_seen" => 1
            ]);
    }

    public static function fetch(int $provider_id,int $client_id,$from,int|string $type = 'all',int $limit = 20): LiveChat
    {
        $data = null;
        $instance = self::init();

        //  this method will fetch latest message from message table according to user_id and provider_id
        $livechat = LiveChat::where("provider_id", $provider_id)->where("client_id", $client_id)->first();
        //  now get message from livechat messages
        $instance->updateUnSeen($livechat->id,$from);
        $liveChatMessages = LiveChatMessage::where("live_chat_id", $livechat->id)
            ->when($type == 0, function ($query){
                $query->where("is_seen", 0);
            })
            ->latest('id')->paginate($limit);

        $liveChatMessages = $liveChatMessages->reverse();
        $livechat->pagination = $liveChatMessages;

        //  check data variable is not empty
        //  now append livechatmessage
        $livechat->messages = $liveChatMessages;
        $livechat->provider = $instance->fetch_provider_info($livechat->provider_id);
        $livechat->client = $instance->fetch_client_info($livechat->client_id);
        $livechat->allow_load_more = LiveChatMessage::where("live_chat_id", $livechat->id)
                ->when($type == 0, function ($query){
                    $query->where("is_seen", 0);
                })->count() > $limit;

        //  now return livechat message collections and if vendor not empty append vendor information
        return $livechat;
    }

    /**
     * @throws Exception
     */
    #[NoReturn]
    public static function send(int $client_id,int $provider_id,?string $message,int $messageFrom,$file = null,?int $project_id = null, $type = null, $proposal_id = null, $interview_message = null, $responseType = 'html'): View|Factory|array|string|Application|null
    {
        //  this method will send message and also store the message to livechat_messages table in database
        //  message column value should be json format when product id is not empty
        //  after done all action then run a event for pusher
        //  response should be view, array and string
        //  create an instance of this class
        $instance = self::init();


        $instance->liveChat = $instance->livechatInstance($client_id, $provider_id);

        //  this message property will store array
        $instance->message["message"] = $message;
        //  assign product information to product value if product not exists then store null
        $instance->message["service"] = $project_id ? $instance->prepareServiceDetails($project_id, $type, $proposal_id, $interview_message) : null;
        //  now check need to upload file for checking and uploading file then call storeFile method
        //  this condition will check file is not empty if empty then do not call storeFile method
        if(!empty($file)){
            //  remember if you call this method then this method should store file and this method couldn't be work without file
            $instance->storeFile($file);
        }


        if(empty($instance->message["message"]) && empty($instance->message["service"]) && empty($file)) {
            return $instance->sendResponse(null, $instance->liveChat, $messageFrom, $responseType);
        }
       
        //  this method will store livechat message
        $message = $instance->storeMessage($messageFrom);
        //  hare will be fired a event for pusher

        //  this event will fire if from user is vendor
        // Get the base URL

        $baseUrl = config('app.url');
        // Generate the full URL for the file
        if (!str_contains($message->file, 'http') && ! empty($message->file)) {
          //  $file_path_full = $baseUrl . '/' . self::FOLDER_PATH . $message->file;
            $file_path_full=asset(self::FOLDER_PATH . $message->file);
        } else {
            $file_path_full = $message->file;
        }
        // Update the file path in the message object
        $message->file = $file_path_full == "" ? null : $file_path_full;
         // Save the changes
        $message->save();

        $instance->fireEvent($message, $instance->liveChat, $messageFrom);

        return $instance->sendResponse($message, $instance->liveChat, $messageFrom, $responseType);
    }

    private function sendResponse($message, $livechat, $messageFrom, $responseType){
        //  this condition will check responseType if response type is json then return json but this is not a good method to return early
        if($responseType == 'json'){
            return [
                "message" => $message,
                "livechat" => $livechat
            ];
        }

        if($messageFrom == 2){
            return view("chat::components.provider.message", [
                "data" => $livechat,
                "message" => $message
            ]);
        }elseif($messageFrom == 1){
            return view("chat::components.client.message", [
                "message" => $message,
                "data" => $livechat,
            ]);
        }
    }

    public function fireEvent($message, $livechat, $messageFrom): void
    {
        if($messageFrom == 2){

            $client_image = $livechat->client?->image;
            $freelancer_image = $livechat->freelancer?->image;

            $messageBlade = view("chat::components.client.message", [
                "data" => $livechat,
                "message" => $message,
                "clientImage" => $client_image,
                "freelancerImage" => $freelancer_image
            ]);

            event(new LivechatVendorMessageEvent(
                $messageBlade,
                $message,
                $livechat,
                $livechat->client_id,
                $livechat->provider_id,
            ));
        }elseif($messageFrom == 1){
            $bladeMessage = view("chat::components.provider.message", [
                "data" => $livechat,
                "message" => $message
            ])->render();

            event(new LivechatUserMessageEvent(
                $bladeMessage,
                $message,
                $livechat,
                $livechat->client_id,
                $livechat->provider_id,
            ));
        }
    }

    private function storeMessage(int $from_user): LiveChatMessage
    {
        return LiveChatMessage::create([
            'live_chat_id' => $this->liveChat?->id,
            'message' => $this->message,
            'file' => $this->filename,
            'from_user' => $from_user,
            'is_seen' => 0
        ]);
    }

    /**
     * @throws Exception
     */
    private function storeFile($file) : void
    {
        $extension = $file->extension();

        // Check if the file extension is allowed
        if (!in_array($extension, $this->allowedFilesExtension)) {
            throw new Exception('The file you have uploaded with '. $extension .' extension are not allowed.');
        }

        $filename = time() . rand(111111,999999) . '.' . $extension;

        $file->move(self::FOLDER_PATH, $filename);

        $this->filename = $filename;
    }

    private function prepareServiceDetails($project_id, $type, $proposal_id, $interview_message): array {
        $project = $this->getServiceDetails($project_id, $type, $proposal_id);

        return [
            'id' => $project->id,
            'service_creator' => $type == 'job'
                ? $project->job_creator?->id
                : ($project->provider_id ?? $project->service_creator?->id ?? $project->admin_id),
            'username' => $type == 'job' ? $project->job_creator?->username : $project->service_creator?->username,
            'title' => $project->title,
            'slug' => $project->slug,
            'image' =>  $type == 'job' ? $project->attachment : $project->image,
            'type' => $type === 'job'
                ? 'job'
                : ($project->provider_id ? 'service' : 'admin-service'),
            'interview_message' =>  $type == 'job' ? $interview_message : ''
        ];
    }

    private function getServiceDetails($project_id, $type, $proposal_id)
    {
        //  check product id is null or not and also check product is integer or not if not integer then throw exception
        if(!is_null($project_id) && (gettype($project_id) == 'integer')){
            //  now query to job or project according to type from job_posts and projects table
            if($type == 'job'){
                $this->projectData = JobPost::select("id","title","slug",'client_id')->with('client')->find($project_id);
            }else{
                $this->projectData = Service::select("id","title","slug","image",'provider_id', 'admin_id')->with('provider', 'admin')->find($project_id);
            }

            if($this->projectData){
                return $this->projectData;
            }

            throw new \RuntimeException(__("Project Not Found"));
        }

        if(is_null($project_id)){
            return null;
        }

        //  now throw exception
        throw new InvalidArgumentException("Invalid id. This id should be integer " . gettype($project_id) . ' given at line:' . __LINE__ . ' File: '. __FILE__);
    }
}
