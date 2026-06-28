<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'title' => 'required|max:191',
            'description' => 'required|min:1',
            'price' => 'required|numeric',
            // other
            'include_service_title.*' => 'required|max:191',
            'include_service_price.*' => 'required|numeric',
            'addons_service_title.*' => 'max:191',
            'faqs_title.*' => 'max:191',

            'all_include_service' => 'nullable|json',
            'all_excludes_service' => 'nullable|json',
            'all_faqs_service' => 'nullable|json',
            'all_addons_service' => 'nullable|json',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'staffs_id' => 'nullable',
            'staffs_id.*' => 'exists:staffs,id', 
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('The title field is required.'),
            'title.max' => __('The title must not exceed 191 characters.'),
            'description.required' => __('The description field is required.'),
            'description.min' => __('The description must be at least 150 characters.'),
            'slug.required' => __('The slug field is required.'),
            'slug.unique' => __('The slug has already been taken.'),
            'price.required' => __('The price field is required.'),
            'price.numeric' => __('The price must be a numeric value.'),
            // other
            'include_service_title.*.required' => __('Title is required'),
            'include_service_price.*.required' => __('Price is required'),
            'include_service_price.*.numeric' => __('Price must be a number'),
            'include_service_quantity.*.required' => __('Quantity is required'),
            'include_service_quantity.*.numeric' => __('Quantity must be a number'),
        ];
    }
}
