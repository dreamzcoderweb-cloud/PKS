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
            'name' => [
                'nullable',
                'string',
                'max:50'
            ],
            'vehicle_number' => [
                'nullable',
                'string',
                'max:50'
            ],
            'driver_number' => [
                'nullable',
                'string',
                'max:50'
            ],
            'status' => 'nullable|integer|in:0,1'
        ];
    }
}
