<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        // Get the vehicle ID from the route parameter (which could be the ID or Model)
        $vehicleParam = $this->route('vehicle');
        $vehicleId = is_numeric($vehicleParam) ? $vehicleParam : ($vehicleParam?->vehicle_id ?? null);

        // Find the vehicle to get its current type if not provided in the request
        $vehicle = null;
        if ($vehicleId) {
            $vehicle = Vehicle::find($vehicleId);
        }

        $vehicleType = $this->input('vehicle_type') ?? ($vehicle?->vehicle_type);

        $rules = [
            'vehicle_type' => [
                'sometimes',
                'required',
                Rule::in(['lorry', 'local'])
            ],
            'status' => 'sometimes|required|integer|in:0,1'
        ];

        if ($this->has('name') || $this->has('vehicle_number') || $this->has('driver_number')) {
            $rules['name'] = [
                'nullable',
                'string',
                'max:50'
            ];
            $rules['vehicle_number'] = [
                'nullable',
                'string',
                'max:50'
            ];
            $rules['driver_number'] = [
                'nullable',
                'string',
                'max:50'
            ];
        }


        return $rules;
    }
}
