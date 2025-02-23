<?php

namespace App\Http\Requests\Course;

use App\Helpers\ApiResponseTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class UpdateCourseRequest extends FormRequest
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
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date_format:d-m-Y H:i',
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.string' => 'The :attribute field must be of type string.',
            'title.max' => 'The :attribute field must be at most :max characters.',
            'description.string' => 'The :attribute field must be of type string.',
            'description.max' => 'The :attribute field must be at most :max characters.',
            'start_date.string' => 'The :attribute field must be of type string.',
            'start_date.date_format' => 'The :attribute must be in the format d-m-Y H:i.',
        ];
    }

    /**
     * @return void
     */
    protected function passedValidation(): void
    {
        Log::info('Update Course Validation Successful');
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
