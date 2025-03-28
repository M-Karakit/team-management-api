<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class AssignInstructorToCourseRequest extends FormRequest
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
        $instructor = $this->route('instructor');

        return [
            'courses' => 'required|array',
            'course.*.id' => 'required|exists:courses,id',
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
        Log::info('Validation passed for assign instructor');
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
