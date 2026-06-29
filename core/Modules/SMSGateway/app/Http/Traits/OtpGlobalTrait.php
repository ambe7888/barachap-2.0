<?php

namespace Modules\SMSGateway\app\Http\Traits;

use App\Helpers\FlashMsg;
use Exception;
use Illuminate\Support\Facades\Http;
use Modules\SMSGateway\app\Models\SmsGateway;
use Modules\SMSGateway\app\Models\UserOtp;
use Twilio\Rest\Client;

trait OtpGlobalTrait
{
    public string $twilio_number;
    public string|array|object $header;
    public string $headerType = 'OTP';
    public string $method = 'GET';
    public string $apiEndpoint = '';
    public string|array $body = [];
    public string $receiverNumber = '';
    public string $templateId = '';
    public string $user;

    public function sendSms($data, $type='notify', $sms_type='register', $user='user')
    {

        [$receiverNumber, $message] = $data;
        $otpCode = count($data) === 3 ? $data[2] : null;

        $client = $this->config();

        if ($client['gateway'] === 'whatsapp') {
            try {
                $waNumber = preg_replace('/[^0-9]/', '', $receiverNumber);
                $waToken = $client['client']['token'] ?? '';
                $waPhoneId = $client['client']['phone_id'] ?? '';
                $waTemplate = $client['client']['template'] ?? 'otp_template';

                if (empty($waToken) || empty($waPhoneId)) {
                    \Illuminate\Support\Facades\Log::error('WhatsApp API Error: Token or Phone ID is missing in gateway settings.');
                    return false;
                }

                $response = Http::withToken($waToken)->post("https://graph.facebook.com/v17.0/{$waPhoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $waNumber,
                    'type' => 'template',
                    'template' => [
                        'name' => $waTemplate,
                        'language' => ['code' => 'fr'],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $otpCode ?? $message
                                    ]
                                ]
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => '0',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $otpCode ?? $message
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    return true;
                }
                
                $errorMsg = $response->body();
                \Illuminate\Support\Facades\Log::error('WhatsApp API Error: ' . $errorMsg);
                throw new \Exception('Meta API: ' . $errorMsg);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('WhatsApp API Exception: ' . $e->getMessage());
                throw $e;
            }
        }

        if($client['gateway'] === 'nexmo'){
            $nexmo_from = get_static_option('site_title');
            try {
                $sms_gateway = $this->activeSmsGateway();

                $credentials = json_decode($sms_gateway->credentials);
                $nexmo_api_key = $credentials->nexmo_api_key;
                $nexmo_api_secret = $credentials->nexmo_api_secret;

                $basic  = new \Nexmo\Client\Credentials\Basic($nexmo_api_key, $nexmo_api_secret);
                $client = new \Nexmo\Client($basic);

                $message = $client->message()->send([
                    'to' => $receiverNumber,
                    'from' => $nexmo_from,
                    'text' => $message
                ]);

                // Check if the response indicates success
                if ($message['status'] == 0) {
                    FlashMsg::error(__('SMS sent successfully'));
                } else {
                    FlashMsg::error(__('Failed to send SMS'));
                }

            } catch (\Exception $e) {
                info("Error: ". $e->getMessage());
            }

        }elseif($client['gateway'] === 'twilio'){
            try {

                $client['client']->messages->create($receiverNumber, [
                    'from' => $this->twilio_number,
                    'body' => $message
                ]);

            } catch (\Twilio\Exceptions\RestException $e) {
                $errorResponse = $this->handleTwilioError($e->getCode());
                return response()->json([
                    'message' => $errorResponse['message'],
                    'status' => 'error'
                ], $errorResponse['status']);
            }

        }else {
            $this->user = $user;
            // type  otp or notify
            // sms_type : register or order
            $otp_or_order_id = count($data) === 3 ? $data[count($data)-1] : 000000;
            $client = $this->otherOperator($client['gateway'], $type,'POST')
            ->setBody(str_replace('+','',$receiverNumber), $sms_type, $otp_or_order_id)->match();

            if ($client->ok())
            {
                return true;
            }
            return false;
        }


        return true;
    }

    public function generateOtp($phone_number, $model_name = 'User', $column_name = 'phone', $relation_name = 'otpInfo', $is_module = false, $module_name = null)
    {
        $user = $this->getModel($model_name, $is_module, $module_name)::select('id')->where($column_name, $phone_number)->first();

        // for user  phone number change
        if (empty($user)){
            $user = $this->getModel($model_name, $is_module, $module_name)::select('id', 'phone')->where('id', auth()->guard('sanctum')->user()->id)->first();
        }

        $userOtp = $user->$relation_name;

        if ($user) {
            $gateway = $this->activeSmsGateway();
            $now = now();
            $otp_expire_time = $gateway->otp_expire_time ?? 1;
            $times = $otp_expire_time == 30 ? 'addSeconds' : 'addMinutes';
            if ($userOtp) {
                $userOtp->update([
                    'user_id' => $user->id,
                    'otp_code' => rand(111111, 999999),
                    'expire_date' => $now->$times($otp_expire_time)
                ]);
            } else {
                $userOtp = UserOtp::create([
                    'user_id' => $user->id,
                    'otp_code' => rand(111111, 999999),
                    'expire_date' => $now->$times($otp_expire_time)
                ]);
            }
        }

        return $userOtp;
    }

    private function getModel($model_name = 'User', $is_module = false, $module_name = null): string
    {
        // Use CamelCase for Model and Module Name
        $model_path = '';
        if ($is_module) {
            $model_path = 'Modules\\' . ucwords($module_name) . '\app\Http\\' . ucwords($model_name);
        } else {
            $model_path = '\App\Models\\' . ucwords($model_name);
        }

        return $model_path;
    }

    private function activeSmsGateway()
    {
        return SMSGateway::active()->first();
    }

    private function config()
    {
        $sms_gateway = $this->activeSmsGateway();
        $client = '';

        try {
            retry(2, function () use ($sms_gateway, &$client) {
                $client = null;

                if ($sms_gateway->name == 'nexmo') {
                    $credentials = $this->nexmoConfig($sms_gateway);
                    $basic  = new \Nexmo\Client\Credentials\Basic($credentials[0], $credentials[1]);
                    $client = new \Nexmo\Client($basic);
                }

                if ($sms_gateway->name == 'twilio') {
                    $credentials = $this->twilioConfig($sms_gateway);
                    $client = new Client(...$credentials);
                }

                if ($sms_gateway->name == 'whatsapp') {
                    $credentials = json_decode($sms_gateway->credentials);
                    $client = [
                        'token' => $credentials->whatsapp_cloud_token ?? '',
                        'phone_id' => $credentials->whatsapp_phone_number_id ?? '',
                        'template' => $credentials->whatsapp_otp_template_name ?? 'otp_template',
                    ];
                }
            }, 1000);
        } catch (Exception $e) {
            info("error: " . $e->getMessage());
        }

        return [
                'gateway' => $sms_gateway?->name ?? '',
                'client' => $client ?? []
            ];
    }

    private function otherOperator($sms_gateway, $type, $method)
    {
        $this->headerType = $type;
        $headerFunction = $sms_gateway.'Header';
        $header = $this->$headerFunction();
        $this->header = Http::withHeaders($header);
        $this->method = $method;
        return $this;
    }

    private function setBody($receiverNumber, $sms_type, $otp_or_order_id = 000000)
    {
        $this->setActiveTemplate($sms_type);
        $this->receiverNumber = $receiverNumber;

        if (strtolower($this->headerType) === 'otp')
        {
            $this->body = [
                "template_id" => $this->templateId,
                "recipients" => [
                    [
                        "mobiles" => $this->receiverNumber,
                        "CODE" => $otp_or_order_id,
                        "NAME" => "user",
                        "SITENAME" => get_static_option('site_title')
                    ]
                ]
            ];
        } else {
            if (strtolower($sms_type) === 'register')
            {
                $this->body = [
                    "template_id" => $this->templateId,
                    "recipients" => [
                        [
                            "mobiles" => $this->receiverNumber,
                            "SITENAME" => get_static_option('site_title')
                        ]
                    ]
                ];
            } else {
                $this->body = [
                    "template_id" => $this->templateId,
                    "recipients" => [
                        [
                            "mobiles" => $this->receiverNumber,
                            "ORDER" => $otp_or_order_id,
                            "SITENAME" => get_static_option('site_title')
                        ]
                    ]
                ];
            }
        }

        return $this;
    }

    private function match()
    {
        if (in_array(strtolower($this->method), ['get','post']))
        {
            $method = strtolower($this->method);

            if (!empty($this->body))
            {
                return $this->header->$method($this->apiEndpoint, $this->body);
            }

            return $this->header->$method($this->apiEndpoint);
        }

        return response("Invalid http type exception", 400);
    }

    private function msg91Header()
    {
        $config = $this->msg91Config();
        $this->apiEndpoint = 'https://control.msg91.com/api/v5/flow/';

        return [
            'accept' => 'application/json',
            'authkey' => $config['auth_key'],
            'content-type' => 'application/json'
        ];
    }

    private function nexmoConfig($sms_gateway): array
    {
        $credentials = json_decode($sms_gateway->credentials);
        $nexmo_api_key = $credentials->nexmo_api_key;
        $nexmo_api_secret = $credentials->nexmo_api_secret;

        return [$nexmo_api_key, $nexmo_api_secret];
    }

    private function twilioConfig($sms_gateway): array
    {
        $credentials = json_decode($sms_gateway->credentials);
        $account_sid = $credentials->twilio_sid;
        $auth_token = $credentials->twilio_auth_token;
        $this->twilio_number = $credentials->twilio_number;

        return [$account_sid, $auth_token];
    }

    private function msg91Config(): array
    {
        $sms_gateway = $this->activeSmsGateway();
        $credentials = json_decode($sms_gateway->credentials);
        $auth_key = $credentials->msg91_auth_key;
        $msg91_otp_template_id = $credentials->msg91_otp_template_id;
        $msg91_notify_user_register_template_id = $credentials->msg91_notify_user_register_template_id;

        return [
            'auth_key' => $auth_key,
            'opt_template_id' => $msg91_otp_template_id,
            'notify_user_register_template_id' => $msg91_notify_user_register_template_id,
        ];
    }

    private function setActiveTemplate($sms_type)
    {
        $config = $this->msg91Config();

        $template_id = '';
        if (strtolower($this->headerType) === 'otp')
        {
            $template_id = $config['opt_template_id'];
        } else {
            if (strtolower($sms_type) === 'register')
            {
                $template_id = $config["notify_".$this->user."_register_template_id"];
            } else {
                $template_id = $config["notify_".$this->user."_order_template_id"];
            }
        }

        $this->templateId = $template_id;
    }


    private function handleTwilioError($errorCode)
    {
        $errorResponses = [
            30001 => ['status' => 429, 'message' => __("Queue overflow. Please try again later.")],
            30002 => ['status' => 403, 'message' => __("Your account is suspended. Please contact support.")],
            30003 => ['status' => 400, 'message' => __("Unreachable destination handset. Please check the number.")],
            30004 => ['status' => 403, 'message' => __("Message blocked. The recipient cannot receive messages.")],
            30005 => ['status' => 400, 'message' => __("Unknown destination handset. Please check the number.")],
            30006 => ['status' => 400, 'message' => __("The destination number is a landline or unreachable.")],
            30007 => ['status' => 403, 'message' => __("Carrier violation. The message content was flagged.")],
            30008 => ['status' => 500, 'message' => __("Unknown error. Please try again later.")],
            30009 => ['status' => 400, 'message' => __("Missing segment. One or more segments of your message were not received.")],
            30010 => ['status' => 402, 'message' => __("Message price exceeds max price. The price of your message exceeds the allowed maximum.")],
            63001 => ['status' => 401, 'message' => __("Channel authentication failed. Check your credentials.")],
            63002 => ['status' => 404, 'message' => __("Channel could not find From address. Verify the channel endpoint address.")],
            63003 => ['status' => 404, 'message' => __("Channel could not find To address. The destination address is incorrect.")],
            63005 => ['status' => 400, 'message' => __("Channel did not accept the given content. Please check the content format.")],
            63006 => ['status' => 400, 'message' => __("Could not format the given content for the channel.")],
            63007 => ['status' => 404, 'message' => __("Twilio could not find a Channel with the specified From address.")],
            63008 => ['status' => 500, 'message' => __("Could not execute the request due to misconfigured channel module. Please check your configuration.")],
            63009 => ['status' => 500, 'message' => __("Channel returned an error during execution. See the specific error for more information.")],
            63010 => ['status' => 500, 'message' => __("Channels - Twilio Internal error.")],
            63012 => ['status' => 500, 'message' => __("Channel returned an internal error that prevented request completion.")],
            63013 => ['status' => 403, 'message' => __("Message send failed due to violation of Channel provider's policy.")],
            63014 => ['status' => 403, 'message' => __("Message delivery failed because it was blocked by a user action.")],

            // Common Twilio error codes
            20001 => ['status' => 400, 'message' => __("The 'To' number is not a valid phone number.")],
            20002 => ['status' => 400, 'message' => __("The 'From' number is not a valid phone number.")],
            20003 => ['status' => 400, 'message' => __("The 'To' number is not reachable.")],
            20004 => ['status' => 400, 'message' => __("The 'To' number is not SMS-capable.")],
            21211 => ['status' => 400, 'message' => __("The phone number provided is not a valid phone number.")],
            21601 => ['status' => 400, 'message' => __("The message could not be delivered to the recipient.")],
            21602 => ['status' => 400, 'message' => __("The message failed because the recipient was unable to receive it.")],
            21603 => ['status' => 400, 'message' => __("The message was rejected by the recipient's carrier.")],
            21604 => ['status' => 400, 'message' => __("The message was rejected by the recipient's phone.")],
            21605 => ['status' => 400, 'message' => __("The message was not delivered due to a network issue.")],

            // Add any additional Twilio error codes and custom responses here
            'default' => ['status' => 500, 'message' => __("An error occurred. Please try again later.")]
        ];

        return $errorResponses[$errorCode] ?? $errorResponses['default'];
    }
}
