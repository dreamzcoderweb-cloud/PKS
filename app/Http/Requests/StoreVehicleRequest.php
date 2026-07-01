<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
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
            'vehicle_type' => [
                'required',
                Rule::in(['lorry', 'local'])
            ],
            'vehicle_number' => [
                Rule::requiredIf($this->input('vehicle_type') === 'lorry'),
                'nullable',
                'string',
                'max:50'
            ],
            'driver_number' => [
                Rule::requiredIf($this->input('vehicle_type') === 'local'),
                'nullable',
                'digits_between:10,15'
            ],
            'status' => 'nullable|integer|in:0,1'
        ];
    }
}
