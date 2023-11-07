<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLevelRequest extends FormRequest
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
            'name' => ['required','regex:/(^[A-Za-z0-9 ]+$)+/', 'max:255'],
            'description' => 'required|string|nullable',
            'initial_output' => 'required',
            'expected_output' => 'required'
        ];

    }
}
