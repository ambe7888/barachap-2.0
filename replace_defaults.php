<?php
$file = 'c:\\xampp\\htdocs\\barachap-2.0\\core\\Modules\\WhatsAppBookingSystem\\app\\Http\\Services\\WhatsAppService.php';
$content = file_get_contents($file);

$replacements = [
    "'Please choose a service:'" => "'Veuillez sélectionner un service:'",
    "'Select Service'" => "'Choisir ce service'",
    "'Included-Excluded'" => "'Inclus / Exclus'",
    "'Show FAQs'" => "'Voir les FAQs'",
    "'Order Now'" => "'Commander'",
    "'Please choose a addon:'" => "'Souhaitez-vous des options supplémentaires ?'",
    "'Select Addons'" => "'Options en plus'",
    "'Please choose a staff:'" => "'Veuillez sélectionner un professionnel:'",
    "'Select Staff'" => "'Choisir le staff'",
    "'Please choose a slot:'" => "'Veuillez choisir un créneau:'",
    "'Select Slot'" => "'Choisir un créneau'",
    "'Please select quantity:'" => "'Veuillez choisir la quantité:'",
    "'Select Quantity'" => "'Choisir la quantité'",
    "'Search Service'" => "'Chercher un service'",
    "'View Recent Orders'" => "'Mes commandes'",
    "'Talk to Support'" => "'Parler au support'",
    "'How can we help you?'" => "'Bonjour, comment pouvons-nous vous aider aujourd\\'hui ?'",
    "'Do you want to cancel your order?'" => "'Voulez-vous annuler votre commande ?'",
    "'Yes, Cancel Order'" => "'Oui, annuler'",
    "'Do not cancel order'" => "'Non, retour'",
    "'Do you want to confirm your order?'" => "'Voulez-vous confirmer votre commande ?'",
    "'Service Details'" => "'Détails du service'",
    "'Other Details'" => "'Autres détails'",
    "'Confirm Order'" => "'Confirmer commande'",
    "'Cancel Order'" => "'Annuler la commande'",
    "'Please choose a location:'" => "'Veuillez choisir le lieu de la prestation:'",
    "'Select Location'" => "'Choisir le lieu'"
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replacements done.";
