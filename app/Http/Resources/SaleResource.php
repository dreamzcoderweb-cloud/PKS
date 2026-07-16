<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'branch_id' => $this->branch_id,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'dealer_id' => $this->dealer_id,
            'dealer' => new DealerResource($this->whenLoaded('dealer')),
            'dealer_name' => $this->dealer?->name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'vehicle_number' => $this->vehicle?->name,
            'invoice_number' => $this->invoice_number,
            'driver_name' => $this->driver_name,
            'driver_number' => $this->driver_number,
            'sale_date' => $this->sale_date?->toIso8601String(),
            'sale_images' => array_map(function ($image) {
                return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
            }, $this->sale_images ?? []),
            'created_by' => $this->created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'details' => SaleDetailResource::collection($this->whenLoaded('details')),
            'total_items' => $this->details ? $this->details->count() : 0,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
