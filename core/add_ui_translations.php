<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$newKeys = [
    "Hello 👋" => "Bonjour 👋",
    "Featured Services" => "Services en vedette",
    "Summary" => "Résumé",
    "Order Summery" => "Récapitulatif de la commande",
    "Continue" => "Continuer",
    "Back" => "Retour",
    "Upload Photos" => "Ajouter des photos",
    "Upload relevant photos to help understand what you need" => "Téléchargez des photos pertinentes pour aider à comprendre votre besoin",
    "Add Photo" => "Ajouter une photo"
];

foreach ($newKeys as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully updated UI translations in fr_FR.json!\n";
?>
