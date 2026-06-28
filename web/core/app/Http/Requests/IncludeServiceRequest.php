<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncludeServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'include_service_title' => 'required|array|min:1',
            'include_service_title.*' => 'required|string|max:255',
            'include_service_description' => 'required|array|min:1',
            'include_service_description.*' => 'required|string|max:255',
        ];
    }
}
