<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'dealer_id' => 'required|exists:dealers,id',
            'lot_number' => 'required|string|max:255',
            'transporter_id' => 'required|exists:transporters,transporter_id',
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'invoice_number' => 'required|string|max:255',
            'driver_number' => 'required|string|max:255',
            'sale_images' => 'required|array|min:2|max:3',
            'sale_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'details' => 'required|array|min:1',
            'details.*.brand_name' => 'required|string|max:255',
            'details.*.stock_name' => 'required|string|max:255',
            'details.*.lot_number' => 'required|string|max:255',
            'details.*.unit_value' => 'required|numeric|min:0',
            'details.*.unit_type' => 'required|string|max:255',
            'details.*.alter_unit_value' => 'required|numeric|min:0',
            'details.*.alter_unit_type' => 'required|string|max:255',
        ];
    }
}
