<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'purchase_id' => $this->purchase_id,
            'brand_name' => $this->brand_name,
            'stock_name' => $this->stock_name,
            'lot_number' => $this->lot_number,
            'unit_value' => $this->unit_value,
            'unit_type' => $this->unit_type,
            'alter_unit_value' => $this->alter_unit_value,
            'alter_unit_type' => $this->alter_unit_type,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
