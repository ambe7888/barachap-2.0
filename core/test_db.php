<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gateway = \Modules\SMSGateway\app\Models\SmsGateway::where('name', 'whatsapp')->first();
echo $gateway->credentials;
