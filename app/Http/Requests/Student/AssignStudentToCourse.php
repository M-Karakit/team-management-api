<?php

namespace App\Http\Requests\Student;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class AssignStudentToCourse extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'courses' => 'required|array',
            'courses.*.id' => 'required|exists:courses,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'course.*.id' => 'Course ID',
        ];
    }

    public function messages(): array
    {
        return [
            'course.*.id.required' => 'The :attribute field is required.',
            'course.*.id.exists' => 'The selected :attribute does not exist.',
        ];
    }

    /**
     * @return void
     */
    public function passedValidation(): void
    {
        Log::info('Validation passed for assign student');
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
