<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$messages = [
    'order_complete' => 'Votre commande est confirmée. Merci de nous faire confiance.',
    'help_message' => 'Bonjour, comment pouvons-nous vous aider aujourd\'hui ?',
    'search_service' => 'Veuillez entrer le nom du service que vous recherchez.',
    'not_available_slots' => 'Désolé, aucun créneau n\'est disponible.',
    'service_not_found' => 'Désolé, nous n\'avons pas trouvé ce service.',
    'cancel_confirmation' => 'Votre commande a bien été annulée.',
    'not_found_recent_order' => 'Aucune commande récente n\'a été trouvée.',
    'ask_user_location' => 'Veuillez nous indiquer votre adresse.',
    'ask_service_select' => 'Veuillez sélectionner un service.',
    'ask_addon_select' => 'Souhaitez-vous des options supplémentaires ?',
    'ask_select_addon_quantity' => 'Veuillez choisir la quantité.',
    'ask_select_staff' => 'Veuillez sélectionner un professionnel.',
    'ask_select_location' => 'Veuillez choisir le lieu de la prestation.',
    'ask_select_slot' => 'Veuillez choisir un créneau horaire.',
    'ask_provide_date' => 'Veuillez fournir une date.',
];

foreach ($messages as $key => $message) {
    if (!get_whatsapp_option("whatsapp_message_$key")) {
        update_whatsapp_option("whatsapp_message_$key", $message);
    }
}

$buttonTexts = [
    'service_search' => 'Chercher un service',
    'view_recent_orders' => 'Mes commandes',
    'talk_to_support' => 'Parler au support',
    'select_service' => 'Choisir ce service',
    'included_excluded' => 'Inclus / Exclus',
    'show_faqs' => 'Voir les FAQs',
    'order_now' => 'Commander',
    'select_addons' => 'Options en plus',
    'select_addons_quantity' => 'Choisir la quantité',
    'select_staff' => 'Choisir le staff',
    'select_location' => 'Choisir le lieu',
    'select_slot' => 'Choisir un créneau',
    'order_service_details' => 'Détails du service',
    'order_other_details' => 'Autres détails',
    'confirm_order' => 'Confirmer commande',
    'cancel_order' => 'Annuler la commande',
    'agree_to_cancel_order' => 'Oui, annuler',
    'disagree_to_cancel_order' => 'Non, retour',
];

foreach ($buttonTexts as $key => $text) {
    if (!get_whatsapp_option("whatsapp_button_text_$key")) {
        update_whatsapp_option("whatsapp_button_text_$key", $text);
    }
}

echo "Default messages added successfully!\n";
