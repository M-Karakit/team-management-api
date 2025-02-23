<?php

namespace App\Http\Requests\Auth;

use App\Helpers\ApiResponseTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
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
            'email' => 'required|string',
            'password' => 'required|min:8',
        ];
    }

    /**
     * Customize the field names for validation messages.
     *
     * @return array<string, string>
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email' => 'Email Address',
            'password' => 'Password',
        ];
    }

    /**
     * Customize the validation messages.
     *
     * @return array<string, string>
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The :attribute field is required',
            'email.email' => 'The :attribute field must be a valid email address',
            'email.max' => 'The :attribute may not be greater than :max characters.',
            'password.required' => 'The :attribute field is required.',
            'password.min' => 'The :attribute must be at least :min characters long.',
        ];
    }

    /**
     * Perform any actions after successful validation.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        Log::info('Login Validation Successful');
    }

    /**
     * @param Validator $validator
     * @return mixed
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): mixed
    {
        $error = $validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($error, 'Validation error', 422));
    }
}
