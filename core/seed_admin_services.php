<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Database\Seeders\AdminServicesSeeder;

echo "Démarrage de l'insertion des services Admin...\n";
$seeder = new AdminServicesSeeder();
$seeder->run();
echo "Succès ! Les 16 catégories, sous-catégories et services ont été ajoutés/mis à jour pour l'Admin.\n";
