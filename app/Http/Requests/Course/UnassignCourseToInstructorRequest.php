<?php

namespace App\Http\Requests\Course;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class UnassignCourseToInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() || auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'instructors' => 'required|array',
            'instructors.*.id' => 'required|exists:instructors,id',
        ];
    }

    /**
     * @return string[]
     */
    public  function attributes(): array
    {
        return [
            'instructors.*.id' => 'Instructor ID',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'instructors.*.id.required' => 'The :attribute field is required.',
            'instructors.*.id.exists' => 'The selected :attribute does not exist.',
        ];
    }

    /**
     * @return void
     */
    public function passedValidation(): void
    {
        Log::info('Validation passed for unassign course');
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors(),
        ]));
    }
}
