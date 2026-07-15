<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'purchase_id' => $this->purchase_id,
            'branch_id' => $this->branch_id,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'dealer_id' => $this->dealer_id,
            'dealer' => new DealerResource($this->whenLoaded('dealer')),
            'lot_number' => $this->lot_number,
            'transporter_id' => $this->transporter_id,
            'transporter' => new TransporterResource($this->whenLoaded('transporter')),
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'driver_number' => $this->driver_number,
            'purchase_images' => array_map(function ($image) {
                return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
            }, $this->purchase_images ?? []),
            'created_by' => $this->created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'details' => PurchaseDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
