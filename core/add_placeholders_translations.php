<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$placeholders = [
    "Enter the verification that was sent to the email." => "Entrez le code de vérification envoyé par e-mail.",
    "Enter Email" => "Entrez votre e-mail",
    "Enter phone" => "Entrez votre téléphone",
    "Enter the verification that was sent to the phone" => "Entrez le code de vérification envoyé sur votre téléphone",
    "Enter your current password" => "Entrez votre mot de passe actuel",
    "Enter new password" => "Entrez le nouveau mot de passe",
    "Re-enter new password" => "Réécrivez le nouveau mot de passe",
    "Enter title" => "Entrez le titre",
    "Enter zip code" => "Entrez le code postal",
    "Enter username" => "Entrez le nom d'utilisateur",
    "Enter password" => "Entrez votre mot de passe",
    "Enter valid email address" => "Entrez une adresse e-mail valide",
    "Enter the OTP" => "Entrez le code OTP",
    "Search or Chose a Category" => "Rechercher ou choisir une catégorie",
    "Search location" => "Rechercher un emplacement",
    "Enter code" => "Entrez le code",
    "Enter address" => "Entrez l'adresse",
    "Enter a valid amount" => "Entrez un montant valide",
    "Enter a valid name" => "Entrez un nom valide",
    "Enter a valid phone number" => "Entrez un numéro de téléphone valide",
    "Enter a valid reason." => "Entrez un motif valide.",
    "Enter order decline reason." => "Entrez le motif de refus de la commande.",
    "Enter a comment about your experience" => "Écrivez un commentaire sur votre expérience",
    "Search Service" => "Rechercher un service",
    "Enter a valid address." => "Entrez une adresse valide.",
    "Enter new email" => "Entrez la nouvelle adresse e-mail",
    "Enter last name" => "Entrez le nom",
    "Enter first name" => "Entrez le prénom",
    "Enter description" => "Entrez la description",
    "Enter your budget" => "Entrez votre budget",
    "Search category" => "Rechercher une catégorie",
    "Enter job title" => "Entrez le titre de la demande",
    "Search city" => "Rechercher une ville",
    "Search state" => "Rechercher une région",
    "Search area" => "Rechercher un quartier",

    // Reset password / Forgot password title
    "Forgot password" => "Mot de passe oublié",
    "Forgot Password" => "Mot de passe oublié",
    "Forgot Password?" => "Mot de passe oublié ?",
    "Send Verification Code" => "Envoyer le code de vérification",
    "Back to Sign In" => "Retour à la connexion",
    "Back To Login" => "Retour à la connexion",
];

foreach ($placeholders as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully updated field placeholders & forget password translations in fr_FR.json!\n";
?>
