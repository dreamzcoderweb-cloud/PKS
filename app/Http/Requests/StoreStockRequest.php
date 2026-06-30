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
        return [
            'brand_name' => 'required|string|max:255',
            'stock_name' => 'required|string|max:255',
            'lott_number' => 'required|string|max:255',
            'units' => 'required|integer|min:0',
            'mt' => 'required|numeric|min:0',
        ];
    }
}
