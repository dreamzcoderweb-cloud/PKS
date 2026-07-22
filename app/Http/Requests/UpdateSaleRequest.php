<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
        $isAdmin = $this->is('api/admin/*') || $this->is('admin/*');

        return [
            'branch_id' => ($isAdmin ? 'required' : 'nullable') . '|exists:branches,branch_id',
            'dealer_id' => 'required|exists:dealers,id',
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'invoice_number' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
            'driver_number' => 'required|string|max:255',
            'sale_date' => 'required|date',
            'sale_images' => 'nullable|array|max:3',
            'sale_images.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'details' => 'required|array|min:1',
            'details.*.stock_id' => 'required|exists:stocks,id',
            'details.*.lot_number' => 'required|string|max:255',
            'details.*.unit_value' => 'required|numeric|min:0',
            'details.*.unit_id' => 'required|exists:units,unit_id',
            'details.*.alternate_unit_value' => 'nullable|numeric|min:0',
            'details.*.alternate_unit_id' => 'nullable|exists:alternate_units,alter_unit_id',
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'dealer_id.required' => 'Dealer Name is required.',
            'vehicle_id.required' => 'Vehicle Number is required.',
            'invoice_number.required' => 'Invoice Number is required.',
            'details.required' => 'At least one sale item is required.',
            'details.min' => 'At least one sale item is required.',
            'details.*.stock_id.required' => 'Stock is mandatory for each sale item.',
            'details.*.lot_number.required' => 'Lot Number is mandatory for each sale item.',
            'details.*.unit_value.required' => 'Unit Value is mandatory for each sale item.',
            'details.*.unit_value.numeric' => 'Unit Value must be numeric.',
            'details.*.unit_id.required' => 'Unit is mandatory for each sale item.',
            'details.*.alternate_unit_value.numeric' => 'Alternate Unit Value must be numeric.',
            'sale_images.max' => 'Maximum 3 images.',
            'sale_images.*.mimes' => 'Allow only valid image formats (JPG, JPEG, PNG).',
        ];
    }
}
