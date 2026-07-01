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
            'order_complete' =>get_whatsapp_option('whatsapp_message_order_complete', ''),
            'help_message' =>get_whatsapp_option('whatsapp_message_help_message', ''),
            'search_service' =>get_whatsapp_option('whatsapp_message_search_service', ''),
            'not_available_slots' =>get_whatsapp_option('whatsapp_message_not_available_slots', ''),
            'service_not_found' =>get_whatsapp_option('whatsapp_message_service_not_found', ''),
            'cancel_confirmation' =>get_whatsapp_option('whatsapp_message_cancel_confirmation', ''),
            'not_found_recent_order' =>get_whatsapp_option('whatsapp_message_not_found_recent_order', ''),
            'ask_user_location' =>get_whatsapp_option('whatsapp_message_ask_user_location', ''),
            'ask_service_select' =>get_whatsapp_option('whatsapp_message_ask_service_select', ''),
            'ask_addon_select' =>get_whatsapp_option('whatsapp_message_ask_addon_select', ''),
            'ask_select_addon_quantity' =>get_whatsapp_option('whatsapp_message_ask_select_addon_quantity', ''),
            'ask_select_staff' =>get_whatsapp_option('whatsapp_message_ask_select_staff', ''),
            'ask_select_location' =>get_whatsapp_option('whatsapp_message_ask_select_location', ''),
            'ask_select_slot' =>get_whatsapp_option('whatsapp_message_ask_select_slot', ''),
            'ask_provide_date' =>get_whatsapp_option('whatsapp_message_ask_provide_date', ''),

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
            'service_search' => get_whatsapp_option('whatsapp_button_text_service_search', ''),
            'view_recent_orders' => get_whatsapp_option('whatsapp_button_text_view_recent_orders', ''),
            'talk_to_support' => get_whatsapp_option('whatsapp_button_text_talk_to_support', ''),
            'select_service' => get_whatsapp_option('whatsapp_button_text_select_service', ''),
            'included_excluded' => get_whatsapp_option('whatsapp_button_text_included_excluded', ''),
            'show_faqs' => get_whatsapp_option('whatsapp_button_text_show_faqs', ''),
            'order_now' => get_whatsapp_option('whatsapp_button_text_order_now', ''),
            'select_addons' => get_whatsapp_option('whatsapp_button_text_select_addons', ''),
            'select_addons_quantity' => get_whatsapp_option('whatsapp_button_text_select_addons_quantity', ''),
            'select_staff' => get_whatsapp_option('whatsapp_button_text_select_staff', ''),
            'select_location' => get_whatsapp_option('whatsapp_button_text_select_location', ''),
            'select_slot' => get_whatsapp_option('whatsapp_button_text_select_slot', ''),
            'order_service_details' => get_whatsapp_option('whatsapp_button_text_order_service_details', ''),
            'order_other_details' => get_whatsapp_option('whatsapp_button_text_order_other_details', ''),
            'confirm_order' => get_whatsapp_option('whatsapp_button_text_confirm_order', ''),
            'cancel_order' => get_whatsapp_option('whatsapp_button_text_cancel_order', ''),
            'agree_to_cancel_order' => get_whatsapp_option('whatsapp_button_text_agree_to_cancel_order', ''),
            'disagree_to_cancel_order' => get_whatsapp_option('whatsapp_button_text_disagree_to_cancel_order', ''),
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
