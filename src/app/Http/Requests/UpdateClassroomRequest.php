<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('classroom');
        return [
            'name' => ['required','regex:/(^[A-Za-z0-9 ]+$)+/', 'max:255', Rule::unique('classrooms', 'bail')->ignore($id),'bail']
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Classroom', 
        ];
    }

    public function messages(): array{
        return ['name.regex' => 'The :attribute field can only contain letters, numbers, and spaces.'];
    }
}
