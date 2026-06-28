<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$moreTranslations = [
    // Bottom Nav & Titles
    "Home" => "Accueil",
    "Orders" => "Commandes",
    "Message" => "Message",
    "Messages" => "Messages",
    "Jobs" => "Demandes",
    "Profile" => "Profil",
    "Order List" => "Liste des commandes",
    "All Jobs" => "Toutes les demandes",

    // Skip
    "Skip" => "Passer",
    "Skip for Later" => "Passer pour le moment",

    // Post Job section
    "Didn't find what you were looking for?" => "Vous n'avez pas trouvé ce que vous cherchez ?",
    "Budget" => "Budget",
    "Enter your budget" => "Entrez votre budget",
    "Enter a valid amount" => "Entrez un montant valide",
    "Description" => "Description",
    "Enter description" => "Entrez la description",
    "Description must contain more then 20 characters" => "La description doit contenir au moins 20 caractères",

    // Profile options (Menu)
    "Favorite Services" => "Services favoris",
    "Rating & Reviews" => "Notes et avis",
    "Refunds" => "Remboursements",
    "Support Ticket" => "Tickets de support",
    "Notifications" => "Notifications",
    "Addresses" => "Adresses",
    "Languages" => "Langues",
    "Terms and Conditions" => "Conditions générales",
    "Privacy Policy" => "Politique de confidentialité",
    "Contact" => "Contact",
    "Delete Account" => "Supprimer le compte",
    "Sign Out" => "Se déconnecter",
    "Sign In" => "Se connecter",
    "Are you sure?" => "Êtes-vous sûr ?",

    // Empty state messages
    "No Orders Found" => "Aucune commande trouvée",
    "No jobs found" => "Aucune demande trouvée",
    "No conversation found." => "Aucune conversation trouvée.",
    "No results found" => "Aucun résultat trouvé",
    "No file selected" => "Aucun fichier sélectionné",
    "No connection found" => "Aucune connexion trouvée",
    "No time selected" => "Aucune heure sélectionnée",
];

foreach ($moreTranslations as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully added all requested app translations to fr_FR.json!\n";
?>
