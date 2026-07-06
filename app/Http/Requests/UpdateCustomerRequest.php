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
        $customerId = $this->route('customer');
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:customers,email,' . $customerId,
            'mobile_number' => 'sometimes|string|max:20|unique:customers,mobile_number,' . $customerId,
            'password' => 'sometimes|string|min:6',
            'branch_id' => 'sometimes|exists:branches,branch_id',
            'status' => 'nullable|integer|in:0,1',
            'business' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
        ];
    }
}
