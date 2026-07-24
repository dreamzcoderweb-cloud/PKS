<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatepassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'dealer_id' => $this->dealer_id,
            'dealer' => new DealerResource($this->whenLoaded('dealer')),
            'dealer_name' => $this->dealer?->name,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_name' => $this->customer?->name,
            'sale_id' => $this->sale_id,
            'sale' => new SaleResource($this->whenLoaded('sale')),
            'purchase_id' => $this->purchase_id,
            'purchase' => new PurchaseResource($this->whenLoaded('purchase')),
            'transporter_id' => $this->transporter_id,
            'transporter' => new TransporterResource($this->whenLoaded('transporter')),
            'transporter_name' => $this->transporter?->name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'vehicle_number' => $this->vehicle?->name,
            'driver_name' => $this->driver_name,
            'driver_number' => $this->driver_number,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'details' => GatepassDetailResource::collection($this->whenLoaded('details')),
            'total_items' => $this->details ? $this->details->count() : 0,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
