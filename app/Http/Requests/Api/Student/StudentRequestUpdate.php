<?php

namespace App\Http\Requests\Api\Student;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequestUpdate extends FormRequest
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
            'firstname'=>['required', 'min:3', 'string'],
            'lastname'=>['required', 'min:3', 'string'],
            'gender'=>['required', 'string'],
            'birthday'=>['nullable', 'string'],
            'birthday_place'=>['nullable', 'string'],
            'nationality'=>['nullable', 'string'],
            'arrival'=>['nullable'],
            'domicile'=>['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response: response()->json([
                'message' => 'failed',
                'data' => $validator->errors(),
            ], 403)
        );
    }
}
