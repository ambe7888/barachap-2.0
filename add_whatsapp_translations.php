<?php
$json_path = 'c:/xampp/htdocs/barachap-2.0/core/resources/lang/fr_FR.json';
$lang = json_decode(file_get_contents($json_path), true);

$new_translations = [
    "Preview Image" => "Image d'aperçu",
    "Example Preview" => "Exemple d'aperçu",
    "This is how it will appear in WhatsApp" => "Voici comment cela apparaîtra dans WhatsApp",
    "WhatsApp Settings" => "Paramètres WhatsApp",
    "Set Default Messages" => "Définir les messages par défaut",
    "Set Button Text" => "Définir le texte des boutons",
    "Rules of Template Create" => "Règles de création de modèle",
    "WhatsApp Verify Token" => "Jeton de vérification WhatsApp",
    "Your whatsapp verify token..." => "Votre jeton de vérification WhatsApp...",
    "WhatsApp Phone Number ID" => "ID du numéro de téléphone WhatsApp",
    "WhatsApp Permanent Token" => "Jeton permanent WhatsApp",
    "Update Settings" => "Mettre à jour les paramètres",
    "WhatsApp Message Template Guide" => "Guide des modèles de message WhatsApp",
    "If you want to message users outside the 24-hour window, you'll need to use approved message templates by Meta." => "Si vous souhaitez envoyer des messages aux utilisateurs au-delà du délai de 24 heures, vous devez utiliser des modèles approuvés par Meta.",
    "Template Name :" => "Nom du modèle :",
    "welcome__template" => "welcome__template",
    "Template Preview" => "Aperçu du modèle",
    "Hi,welcome back! Would you like to book another service with us?" => "Bonjour, bon retour ! Souhaitez-vous réserver un autre service avec nous ?",
    "Buttons:" => "Boutons :",
    "Yes" => "Oui",
    "No" => "Non",
    "How to Create This Template" => "Comment créer ce modèle",
    "Go to " => "Allez dans ",
    "Click " => "Cliquez sur ",
    "Choose a category: " => "Choisissez une catégorie : ",
    "Set the template name: " => "Définissez le nom du modèle : ",
    "Write your message: " => "Rédigez votre message : ",
    "Add interactive buttons (Quick Reply): " => "Ajoutez des boutons interactifs (Réponse rapide) : ",
    "Submit for approval." => "Soumettez pour approbation.",
    "Once approved, use this template from your backend to message users beyond 24 hours of last interaction." => "Une fois approuvé, utilisez ce modèle depuis votre backend pour envoyer des messages aux utilisateurs au-delà de 24 heures."
];

foreach ($new_translations as $key => $value) {
    if (!isset($lang[$key])) {
        $lang[$key] = $value;
    }
}

// Add specifically missing ones
$lang["WhatsApp Settings"] = "Paramètres WhatsApp";
$lang["Set Default Messages"] = "Définir les messages par défaut";
$lang["Set Button Text"] = "Définir le texte des boutons";
$lang["Rules of Template Create"] = "Règles de création de modèle";
$lang["Update Settings"] = "Mettre à jour les paramètres";
$lang["-This token is required during Webhook Verification when setting up your WhatsApp Business API on Meta Developer Portal. Meta will compare this with what your app returns."] = "- Ce jeton est requis lors de la vérification du Webhook lors de la configuration de votre API WhatsApp Business sur le portail des développeurs Meta.";
$lang["-This is the unique identifier for the phone number connected to your WhatsApp Business Account. You can find it in your Meta Business Manager under your WhatsApp assets."] = "- Il s'agit de l'identifiant unique du numéro de téléphone connecté à votre compte WhatsApp Business.";
$lang["-This is the permanent access token generated from your Meta Developer app. It allows your system to continuously send and receive messages without the token expiring."] = "- Il s'agit du jeton d'accès permanent généré depuis votre application Meta Developer. Il permet à votre système d'envoyer et de recevoir des messages en continu.";

file_put_contents($json_path, json_encode($lang, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Translations added.";
