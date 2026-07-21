<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required_without:mobile_number|nullable|string',
            'mobile_number' => 'required_without:email|nullable|string',
            'password' => 'required|string',
            'branch_id' => 'required|integer',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch ID is required.',
            'branch_id.integer' => 'Branch ID must be a valid integer.',
            'email.required_without' => 'Email or mobile number is required.',
            'password.required' => 'Password is required.',
        ];
    }
}
