<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$newKeys = [
    "Select category" => "Sélectionner une catégorie",
    "Search category" => "Rechercher une catégorie",
    "Select Time" => "Sélectionner l'heure",
    "Time selected" => "Heure sélectionnée",
    "Failed to select the time" => "Échec de la sélection de l'heure",
    "No time selected" => "Aucune heure sélectionnée",
    "Time" => "Heure"
];

foreach ($newKeys as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully updated time & category translations in fr_FR.json!\n";
?>
