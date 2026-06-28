<?php

use Illuminate\Support\Facades\Broadcast;



Broadcast::channel('livechat-client-channel.{provider_id}.{client_id}', function ($client, $provider_id){
    return (int) $client->id === (int) $provider_id;
});

Broadcast::channel('livechat-provider-channel.{client_id}.{provider_id}', static function ($provider, $client_id){
    return (int) $provider->id === (int) $client_id;
});
