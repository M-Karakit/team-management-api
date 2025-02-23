<?php

namespace App\Http\Requests\Student;

use App\Helpers\ApiResponseTrait;
use App\Rules\UniqueEmail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StudentRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:100', 'email', new UniqueEmail()],
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Student Name',
            'email' => 'Email Address',
            'password' => 'Password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The :attribute field is required.',
            'name.string' => 'The :attribute field must be of type string.',
            'name.max' => 'The :attribute field must be at most :max characters.',
            'email.required' => 'The :attribute field is required.',
            'email.string' => 'The :attribute field must be of type string.',
            'email.email' => 'The :attribute must be a valid email address.',
            'email.max' => 'The :attribute field must be at most :max characters.',
            'password.required' => 'The :attribute field is required.',
            'password.min' => 'The :attribute must be at least :min characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    /**
     * @return void
     */
    protected function passedValidation(): void
    {
        Log::info('Student Validation Successful');
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
