<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'provider_id' => 'nullable',
            'admin_id' => 'nullable',
            'first_name' => 'required|max:191',
            'last_name' => 'required|min:150',
            'email' => 'required|unique:staff',
            'phone ' => 'required|unique:staff',
            'about ' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ];
    }
}
