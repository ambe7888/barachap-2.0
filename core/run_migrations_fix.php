<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Deleting migration records from database...\n";
\DB::table('migrations')->whereIn('migration', [
    '2025_01_14_061604_add_staffs_id_and_disable_staff_to_services_table',
    '2025_01_14_065109_update_disable_staff_default_in_services_table'
])->delete();

echo "Running migrations...\n";
$output = shell_exec('php artisan migrate 2>&1');
echo $output;
?>
