<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$newKeys = [
    // Cart translations
    "Cart" => "Panier",
    "Clear Cart" => "Vider le panier",
    "No service added yet." => "Aucun service ajouté pour le moment.",
    "Subtotal" => "Sous-total",
    "Tax" => "Taxe",
    "Total" => "Total",

    // Filter translations
    "Reset Filter" => "Réinitialiser",
    "Apply Filter" => "Appliquer les filtres",
    "Price Range" => "Tranche de prix",
    "Rating" => "Note",
    "Service units" => "Unités de service",
    "I'm looking service in" => "Je recherche un service dans",
    "Categories" => "Catégories",
    "Handyman" => "Prestataire",
    "Service" => "Service",

    // Order/Service Not Found translations
    "No orders found!" => "Aucune commande trouvée !",
    "Order not found" => "Commande introuvable"
];

foreach ($newKeys as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully updated Cart & Filter translations in fr_FR.json!\n";
?>
