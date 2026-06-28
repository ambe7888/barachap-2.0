<?php

namespace App\Http\Requests;

use App\Helpers\SanitizeInput;
use Illuminate\Foundation\Http\FormRequest;

class ProviderHandleWithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "gateway_id" => "required",
            "gateway_fields" => "required",
            "amount" => "required",
            "user_id" => "required",
            "status" => "required",
            "fee" => "nullable|numeric",
        ];
    }

    protected function prepareForValidation()
    {

        $user_id = auth('sanctum')->user()->id;

        $fields = [];
        $decodedFields = json_decode($this->gateway_fields, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Handle JSON decode error if needed
            throw new \Exception('Invalid JSON data');
        }
        foreach ($decodedFields ?? [] as $key => $value) {
            $fields[$key] = SanitizeInput::esc_html($value);
        }


        return $this->merge([
            "gateway_id" => $this->gateway_id,
            "gateway_fields" => json_encode($fields),
            "amount" => $this->amount,
            "user_id" => $user_id,
            "status" => "1",
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
