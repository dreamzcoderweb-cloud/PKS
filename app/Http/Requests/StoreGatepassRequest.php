<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGatepassRequest extends FormRequest
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
            'gatepass_number' => 'nullable|string|max:255|unique:gatepasses,gatepass_number',
            'gatepass_type' => 'nullable|string|in:outward,inward',
            'movement_type' => 'nullable|string|in:sale,purchase,transfer,other',
            'branch_id' => 'required|exists:branches,branch_id',
            'dealer_id' => 'nullable|exists:dealers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'transporter_id' => 'nullable|exists:transporters,transporter_id',
            'vehicle_id' => 'nullable|exists:vehicles,vehicle_id',
            'driver_name' => 'nullable|string|max:255',
            'driver_number' => 'nullable|string|max:255',
            'gatepass_date' => 'required|date',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,dispatched,completed,cancelled',
            'gatepass_images' => 'nullable|array|max:5',
            'gatepass_images.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'details' => 'nullable|array',
            'details.*.stock_id' => 'required_with:details|exists:stocks,id',
            'details.*.lot_number' => 'nullable|string|max:255',
            'details.*.unit_value' => 'required_with:details|numeric|min:0',
            'details.*.unit_id' => 'required_with:details|exists:units,unit_id',
            'details.*.alternate_unit_value' => 'nullable|numeric|min:0',
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
            'gatepass_date.required' => 'Gatepass Date is mandatory.',
            'gatepass_images.max' => 'Maximum 5 images allowed.',
            'gatepass_images.*.mimes' => 'Allow only valid image formats (JPG, JPEG, PNG).',
            'details.*.stock_id.required_with' => 'Stock is required for each gatepass item.',
            'details.*.unit_value.required_with' => 'Unit value is required for each gatepass item.',
            'details.*.unit_id.required_with' => 'Unit is required for each gatepass item.',
        ];
    }
}
