<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OrderCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'all_services' => 'required|array',
            'all_services.*.service_id' => 'required|integer|exists:services,id',
            'all_services.*.location_id' => 'required|integer|exists:user_locations,id',
            'all_services.*.date' => 'required|date_format:Y-m-d',
            'all_services.*.schedule' => 'required|string',
            'all_services.*.order_note' => 'nullable|string',

            'addons_services' => 'nullable|array',
            'addons_services.*.addon_service_id' => 'nullable|integer|exists:service_addons,id',
            'addons_services.*.service_id' => 'nullable|integer|exists:services,id',
            'addons_services.*.quantity' => 'nullable|integer|min:1',

            'selected_payment_gateway' => 'required|string',
            'manual_payment_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('manual_payment_image', 'required|mimes:jpg,jpeg,png,webp,pdf', function ($input) {
            return $input->selected_payment_gateway == 'manual_payment';
        });
    }

    public function messages(): array
    {
        return [
            'all_services.required' => __('The all services field is required.'),
            'all_services.array' => __('The all services must be an array.'),

            'all_services.*.service_id.required' => __('The service ID is required for each service.'),
            'all_services.*.service_id.integer' => __('The service ID must be an integer.'),
            'all_services.*.service_id.exists' => __('The selected service ID is invalid.'),

            'all_services.*.staff_id.required' => __('The staff ID is required for each service.'),
            'all_services.*.staff_id.integer' => __('The staff ID must be an integer.'),
            'all_services.*.staff_id.exists' => __('The selected staff ID is invalid.'),

            'all_services.*.location_id.required' => __('The location ID is required for each service.'),
            'all_services.*.location_id.integer' => __('The location ID must be an integer.'),
            'all_services.*.location_id.exists' => __('The selected location ID is invalid.'),

            'all_services.*.date.required' => __('The date is required for each service.'),
            'all_services.*.date.date_format' => __('The date format is invalid. Use YYYY-MM-DD.'),

            'all_services.*.schedule.required' => __('The schedule is required for each service.'),
            'all_services.*.schedule.string' => __('The schedule must be a string.'),

            'all_services.*.order_note.string' => __('The order note must be a string.'),

            'addons_services.array' => __('The addons services must be an array.'),

            'addons_services.*.addon_service_id.integer' => __('The addon service ID must be an integer.'),
            'addons_services.*.addon_service_id.exists' => __('The selected addon service ID is invalid.'),

            'addons_services.*.service_id.integer' => __('The service ID must be an integer.'),
            'addons_services.*.service_id.exists' => __('The selected service ID is invalid.'),

            'addons_services.*.quantity.integer' => __('The quantity must be an integer.'),
            'addons_services.*.quantity.min' => __('The quantity must be at least 1.'),

            'selected_payment_gateway.required' => __('The selected payment gateway is required.'),
            'selected_payment_gateway.string' => __('The selected payment gateway must be a string.'),


            'manual_payment_image.required' => __('The manual payment image is required when using manual payment.'),
            'manual_payment_image.file' => __('The manual payment image must be a file.'),
            'manual_payment_image.mimes' => __('The manual payment image must be a file of type: jpg, jpeg, png, webp, pdf.'),
            'manual_payment_image.max' => __('The manual payment image may not be greater than 2048 kilobytes.'),
        ];
    }

}
