<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealerRequest extends FormRequest
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
            'branch_id' => 'sometimes|exists:branches,branch_id',
            'name' => 'sometimes|string|max:255',
            'business_name' => 'sometimes|string|max:255',
            'contact_number' => 'sometimes|string|max:20|exists:dealers,contact_number',
            'address' => 'sometimes|string',
        ];
    }
}
