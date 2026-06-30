<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'business' => 'sometimes|required|string|max:255',
            'mobile' => 'sometimes|required|string|max:20',
            'location' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'nullable|integer|in:0,1',
        ];
    }
}
