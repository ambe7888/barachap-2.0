<?php
namespace Modules\WhatsAppBookingSystem\app\Http\Controllers;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class WhatsAppBookingSystemController extends Controller
{
    public function whatsappSettingPage()
    {
        $existing_token = get_whatsapp_option('whatsapp_verify_token');
        if (!$existing_token) {
            $token = "prohandy__" . bin2hex(random_bytes(32));
            update_whatsapp_option('whatsapp_verify_token', $token);
        }
        return view('whatsappbookingsystem::Backend.WhatsApp.generate_token');
    }
    //generate token

    public function whatsappSettingUpdate(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'whatsapp_verify_token' => 'required',
            'whatsapp_phone_number_id' => 'required',
            'whatsapp_permanent_token' => 'required',
        ]);
        update_whatsapp_option('whatsapp_verify_token',$request->whatsapp_verify_token);
        update_whatsapp_option('whatsapp_phone_number_id',$request->whatsapp_phone_number_id);
        update_whatsapp_option('whatsapp_permanent_token',$request->whatsapp_permanent_token);
        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function messageSettingPage()
    {
        //get messages
        $messages = [
            'order_complete' =>get_whatsapp_option('whatsapp_message_order_complete', 'Votre commande est confirmée. Merci de nous faire confiance.'),
            'help_message' =>get_whatsapp_option('whatsapp_message_help_message', 'Bonjour, comment pouvons-nous vous aider aujourd\'hui ?'),
            'search_service' =>get_whatsapp_option('whatsapp_message_search_service', 'Veuillez entrer le nom du service que vous recherchez.'),
            'not_available_slots' =>get_whatsapp_option('whatsapp_message_not_available_slots', 'Désolé, aucun créneau n\'est disponible.'),
            'service_not_found' =>get_whatsapp_option('whatsapp_message_service_not_found', 'Désolé, nous n\'avons pas trouvé ce service.'),
            'cancel_confirmation' =>get_whatsapp_option('whatsapp_message_cancel_confirmation', 'Votre commande a bien été annulée.'),
            'not_found_recent_order' =>get_whatsapp_option('whatsapp_message_not_found_recent_order', 'Aucune commande récente n\'a été trouvée.'),
            'ask_user_location' =>get_whatsapp_option('whatsapp_message_ask_user_location', 'Veuillez nous indiquer votre adresse.'),
            'ask_service_select' =>get_whatsapp_option('whatsapp_message_ask_service_select', 'Veuillez sélectionner un service.'),
            'ask_addon_select' =>get_whatsapp_option('whatsapp_message_ask_addon_select', 'Souhaitez-vous des options supplémentaires ?'),
            'ask_select_addon_quantity' =>get_whatsapp_option('whatsapp_message_ask_select_addon_quantity', 'Veuillez choisir la quantité.'),
            'ask_select_staff' =>get_whatsapp_option('whatsapp_message_ask_select_staff', 'Veuillez sélectionner un professionnel.'),
            'ask_select_location' =>get_whatsapp_option('whatsapp_message_ask_select_location', 'Veuillez choisir le lieu de la prestation.'),
            'ask_select_slot' =>get_whatsapp_option('whatsapp_message_ask_select_slot', 'Veuillez choisir un créneau horaire.'),
            'ask_provide_date' =>get_whatsapp_option('whatsapp_message_ask_provide_date', 'Veuillez fournir une date.'),

        ];
        return view('whatsappbookingsystem::Backend.WhatsApp.message_setting', compact('messages'));
    }
    public function messageSettingUpdate(Request $request): RedirectResponse
    {
        foreach ($request->messages as $key => $message) {
            update_whatsapp_option("whatsapp_message_$key", $message);
        }
        return back()->with('success', 'Saved successfully!');
    }

    public function buttonTextSettingPage(Request $request)
    {
        $messages = [
            'service_search' => get_whatsapp_option('whatsapp_button_text_service_search', 'Chercher un service'),
            'view_recent_orders' => get_whatsapp_option('whatsapp_button_text_view_recent_orders', 'Mes commandes'),
            'talk_to_support' => get_whatsapp_option('whatsapp_button_text_talk_to_support', 'Parler au support'),
            'select_service' => get_whatsapp_option('whatsapp_button_text_select_service', 'Choisir ce service'),
            'included_excluded' => get_whatsapp_option('whatsapp_button_text_included_excluded', 'Inclus / Exclus'),
            'show_faqs' => get_whatsapp_option('whatsapp_button_text_show_faqs', 'Voir les FAQs'),
            'order_now' => get_whatsapp_option('whatsapp_button_text_order_now', 'Commander'),
            'select_addons' => get_whatsapp_option('whatsapp_button_text_select_addons', 'Options en plus'),
            'select_addons_quantity' => get_whatsapp_option('whatsapp_button_text_select_addons_quantity', 'Choisir la quantité'),
            'select_staff' => get_whatsapp_option('whatsapp_button_text_select_staff', 'Choisir le staff'),
            'select_location' => get_whatsapp_option('whatsapp_button_text_select_location', 'Choisir le lieu'),
            'select_slot' => get_whatsapp_option('whatsapp_button_text_select_slot', 'Choisir un créneau'),
            'order_service_details' => get_whatsapp_option('whatsapp_button_text_order_service_details', 'Détails du service'),
            'order_other_details' => get_whatsapp_option('whatsapp_button_text_order_other_details', 'Autres détails'),
            'confirm_order' => get_whatsapp_option('whatsapp_button_text_confirm_order', 'Confirmer commande'),
            'cancel_order' => get_whatsapp_option('whatsapp_button_text_cancel_order', 'Annuler la commande'),
            'agree_to_cancel_order' => get_whatsapp_option('whatsapp_button_text_agree_to_cancel_order', 'Oui, annuler'),
            'disagree_to_cancel_order' => get_whatsapp_option('whatsapp_button_text_disagree_to_cancel_order', 'Non, retour'),
        ];

        return view('whatsappbookingsystem::Backend.WhatsApp.button_text_setting',compact('messages'));
    }

    public function buttonTextSettingUpdate(Request $request): RedirectResponse
    {

        $messages = $request->messages;

        foreach ($messages as $key => $message) {
            if (strlen($message) > 20) {
                //dd($message);
                return redirect()->back()->withErrors([
                    "messages.$key" => "Button text for '$key' must be 20 characters or less."
                ])->withInput();
            }
            update_whatsapp_option("whatsapp_button_text_$key", $message);
        }

        return back()->with('success', 'Saved successfully!');

    }

    public function messageTemplateGuide()
    {
        return view('whatsappbookingsystem::Backend.WhatsApp.template_create_rules');
    }
}
