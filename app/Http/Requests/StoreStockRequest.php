<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
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
            'brand_name' => 'required|string|max:255',
            'stock_name' => 'required|string|max:255',
            'lott_number' => 'required|string|max:255',
            'units' => 'required|integer|min:0',
            'mt' => 'required|numeric|min:0',
            'branch_id' => ($isAdmin ? 'required' : 'nullable') . '|exists:branches,branch_id',
            'unit_id' => 'required|exists:units,unit_id',
            'alter_unit_id' => 'required|exists:alternate_units,alter_unit_id',
            'unit_value' => 'required|string|min:0',
            'alter_unit_value' => 'required|string|min:0',
        ];
    }
}
