<?php

    namespace App\Http\Requests\Api\ScholarshipFeed;

    use Illuminate\Contracts\Validation\ValidationRule;
    use Illuminate\Foundation\Http\FormRequest;

    class ScholarshipFeedRequest extends FormRequest
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
                    'normal' => ['required', 'numeric'],
                    'subvention' => ['required', 'numeric']
                ];
            }

            return [
                'classrooms_id' => ['required', 'array'],
                'year_id' => ['required', 'numeric', 'exists:years,id'],
                'normal' => ['required', 'numeric'],
                'subvention' => ['required', 'numeric']
            ];
        }
    }
