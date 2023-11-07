<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignStudentsRequest extends FormRequest
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
        return [
            'students' => ['required', 'array'],
            'students.*.name' => ['required', 'max:255'],
            'students.*.email' => ['required', 'email', 'unique:users,email'],
            'students.*.password' => ['required','min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'students' => 'The students field is required.',
            'students.*.name.required' => 'All names are required.',
            'students.*.name.max' => 'All names must not be greater than 255 characters.',
            'students.*.email.required' => 'All email is required.',
            'students.*.email.email' => 'All email must be a valid email.',
            'students.*.email.unique' => 'All email must be unique.',
            'students.*.password.required' => 'All password is required.',
            'students.*.password.min' => 'All password must be atleast 8 characters long.'
        ];
    }
}
