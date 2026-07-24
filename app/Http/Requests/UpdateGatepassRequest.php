<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGatepassRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,branch_id',
            'dealer_id' => 'nullable|exists:dealers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'transporter_id' => 'nullable|exists:transporters,transporter_id',
            'vehicle_id' => 'nullable|exists:vehicles,vehicle_id',
            'driver_name' => 'nullable|string|max:255',
            'driver_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,dispatched,completed,cancelled',
            'details' => 'nullable|array',
            'details.*.stock_id' => 'required_with:details|exists:stocks,id',
            'details.*.lot_number' => 'nullable|string|max:255',
            'details.*.unit_value' => 'required_with:details|string|min:0',
            'details.*.unit_id' => 'required_with:details|exists:units,unit_id',
            'details.*.alternate_unit_value' => 'nullable|string|min:0',
            'details.*.alternate_unit_id' => 'nullable|exists:alternate_units,alter_unit_id',
            'details.*.remarks' => 'nullable|string',
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch is mandatory.',
        ];
    }
}
