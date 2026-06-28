<?php

namespace Modules\JobPost\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'title' => 'required|max:191',
            'description' => 'required',
            'budget' => 'required|numeric',
            'date' => 'required',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
