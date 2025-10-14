<?php

namespace App\Http\Requests\Api\Receipt;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReceiptRequest extends FormRequest
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
            'payment_id' => ['required', 'numeric', 'exists:payments,id'],
            'amount' => ['required', 'string'],
            'kind' => ['required', 'string', 'in:Espèce,Chèque,virement bancaire'],
            'date' => ['required', 'string'],
            'number' => ['required', 'numeric'],
            'cancelled' => ['required', 'numeric']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response: response()->json([
                'message' => $validator->errors()
            ], 404)
        );
    }
}
