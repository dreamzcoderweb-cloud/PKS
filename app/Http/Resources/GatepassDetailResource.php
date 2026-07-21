<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatepassDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gatepass_id' => $this->gatepass_id,
            'stock_id' => $this->stock_id,
            'stock' => new StockResource($this->whenLoaded('stock')),
            'lot_number' => $this->lot_number,
            'unit_value' => $this->unit_value,
            'unit_id' => $this->unit_id,
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'alternate_unit_value' => $this->alternate_unit_value,
            'alternate_unit_id' => $this->alternate_unit_id,
            'alternate_unit' => new AlternateUnitResource($this->whenLoaded('alternateUnit')),
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
