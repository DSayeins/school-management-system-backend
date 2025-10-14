<?php

    namespace App\Http\Requests\Api\Registration;

    use Illuminate\Contracts\Validation\ValidationRule;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class RegistrationRequest extends FormRequest
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
         * @return array<string, ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            if ($this->method() == 'PUT') {
                return [
                    'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
                    'year_id' => ['required', 'integer', 'exists:years,id'],
                    'presence' => ['required', 'numeric'],
                    'discount' => ['required', 'numeric'],
                    'bourse' => ['required', 'numeric'],
                    'redouble' => ['required', 'numeric'],
                    'kind' => ['required', 'string', 'in:Normal,Subvention'],
                    'periods' => ['required', 'numeric', 'in:1,2,3'],
                    'previous_school' => ['required', 'string'],
                    'brothers' => ['required', 'numeric'],
                    'insurance' => ['required', 'string', 'in:Oui,Non,Décharge'],
                ];
            }

            return [
                'student_id' => ['required', 'integer', 'exists:students,id'],
                'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
                'year_id' => ['required', 'integer', 'exists:years,id'],
                'presence' => ['required', 'numeric'],
                'discount' => ['required', 'numeric'],
                'bourse' => ['required', 'numeric'],
                'redouble' => ['required', 'numeric'],
                'kind' => ['required', 'string', 'in:Normal,Subvention'],
                'periods' => ['required', 'numeric', 'in:1,2,3'],
                'previous_school' => ['required', 'string'],
                'brothers' => ['required', 'numeric'],
                'insurance' => ['required', 'string', 'in:Oui,Non,Décharge'],
            ];
        }

        protected function failedValidation(Validator $validator): HttpResponseException
        {
            return new HttpResponseException(
                response: response()->json(['message' => $validator->errors()])
            );
        }
    }
