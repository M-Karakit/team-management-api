<?php

namespace App\Http\Requests\Instructor;

use App\Helpers\ApiResponseTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class InstructorRequest extends FormRequest
{
    use ApiResponseTrait;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'experience' => 'required|integer|min:1',
            'specialty' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'experience' => 'Experience',
            'specialty' => 'Specialty'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The :attribute field is required.',
            'name.string' => 'The :attribute field must be of type string.',
            'name.max' => 'The :attribute field must be at most :max characters.',
            'experience.required' => 'The :attribute field is required.',
            'experience.integer' => 'The :attribute field must be of type integer.',
            'experience.min' => 'The :attribute field must be at least :min year experience.',
            'specialty.required' => 'The :attribute field is required.',
            'specialty.string' => 'The :attribute field must be of type string.',
            'specialty.max' => 'The :attribute field must be at most :max characters.',
        ];
    }

    /**
     * @return void
     */
    protected function passedValidation(): void
    {
        Log::info('Instructor Validation Successful');
    }

    /**
     * @param Validator $validator
     * @return mixed
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): mixed
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors, 'Validation error', 422));
    }
}
