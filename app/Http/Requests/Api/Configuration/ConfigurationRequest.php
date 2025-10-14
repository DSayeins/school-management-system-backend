<?php

namespace App\Http\Requests\Api\Configuration;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfigurationRequest extends FormRequest
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
        if($this->method() == 'POST') {
            return [
                'year_id' => ['required', 'numeric', 'exists:years,id'],
                'discount' => ['required', 'numeric'],
                'bourse' => ['required', 'numeric'],
                'registration_fees' => ['required', 'numeric'],
                'includeRegistrationFeed' => ['required', 'boolean']
            ];
        }

        return [
            'discount' => ['required', 'numeric'],
            'bourse' => ['required', 'numeric'],
            'registration_fees' => ['required', 'numeric'],
            'includeRegistrationFeed' => ['required', 'boolean']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response: response()->json(['message' => $validator->errors()], 422)
        );
    }
}
