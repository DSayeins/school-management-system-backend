<?php

namespace App\Http\Requests\Api\Student;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentStoreRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'min:3'],
            'lastname' => ['required', 'string', 'min:3'],
            'birthday' => ['nullable', 'date_format:d/m/Y'],
            'birthday_place' => ['nullable', 'string', 'min:3'],
            'gender'=> ['required', 'string'],
            'nationality' => ['nullable', 'string'],
            'domicile' => ['nullable', 'string'],
            'arrival' => ['required', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response: response()->json([
            'message' => 'failed',
            'data' => $validator->errors()->toArray(),
        ], 404));
    }
}
