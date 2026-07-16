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
            'lot_number' => $this->lot_number,
            'transporter_id' => $this->transporter_id,
            'transporter' => new TransporterResource($this->whenLoaded('transporter')),
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'invoice_number' => $this->invoice_number,
            'driver_number' => $this->driver_number,
            'sale_images' => array_map(function ($image) {
                return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
            }, $this->sale_images ?? []),
            'created_by' => $this->created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'details' => SaleDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
