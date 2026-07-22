<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
        $isAdmin = $this->is('api/admin/*') || $this->is('admin/*');

        return [
            'brand_name' => 'sometimes|nullable|string|max:255',
            'stock_name' => 'sometimes|nullable|string|max:255',
            'lott_number' => 'sometimes|nullable|string|max:255',
            'units' => 'sometimes|nullable|integer|min:0',
            'mt' => 'sometimes|nullable|numeric|min:0',
            'branch_id' => ($isAdmin ? 'required' : 'sometimes|nullable') . '|exists:branches,branch_id',
            'unit_id' => 'sometimes|exists:units,unit_id',
            'alter_unit_id' => 'sometimes|exists:alternate_units,alter_unit_id',
            'unit_value' => 'sometimes|nullable|string|min:0',
            'alter_unit_value' => 'sometimes|nullable|string|min:0',
        ];
    }
}
