<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stock_id' => $this->stock_id,
            'brand_name' => $this->brand_name,
            'stock_name' => $this->stock_name,
            'lott_number' => $this->lott_number,
            'units' => (int) $this->units,
            'mt' => (float) $this->mt,
            'stock_code' => $this->stock_code,
            'branch_id' => $this->branch_id,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'unit_id' => $this->unit_id,
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'alter_unit_id' => $this->alter_unit_id,
            'alter_unit' => new AlternateUnitResource($this->whenLoaded('alternateUnit')),
            'unit_value' => $this->unit_value !== null ? (float) $this->unit_value : null,
            'alter_unit_value' => $this->alter_unit_value !== null ? (float) $this->alter_unit_value : null,
            'created_by' => (int) $this->created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
