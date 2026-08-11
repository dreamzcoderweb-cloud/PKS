<?php

namespace App\Http\Resources\Mob\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMobSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'branch_id' => $this->branch_id,
            'branch_name' => $this->branch?->name,
            'dealer_id' => $this->dealer_id,
            'dealer_name' => $this->dealer?->name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle_number' => $this->vehicle?->name,
            'saletype' => (int) ($this->saletype ?? 0),
            'saletype_text' => ($this->saletype ?? 0) == 1 ? 'Decorticate' : 'Sale',
            'invoice_number' => $this->invoice_number,
            'driver_name' => $this->driver_name,
            'driver_number' => $this->driver_number,
            'sale_date' => $this->sale_date?->toIso8601String(),
            'sale_images' => array_map(function ($image) {
                return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
            }, $this->sale_images ?? []),
            'total_items' => $this->details ? $this->details->count() : 0,
            'details' => $this->details ? $this->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'stock_id' => $detail->stock_id,
                    'stock_name' => $detail->stock?->stock_name,
                    'brand_name' => $detail->stock?->brand_name,
                    'lot_number' => $detail->lot_number,
                    'unit_value' => (float) $detail->unit_value,
                    'unit_id' => $detail->unit_id,
                    'unit_name' => $detail->unit?->unit,
                    'alternate_unit_value' => $detail->alternate_unit_value !== null ? (float) $detail->alternate_unit_value : null,
                    'alternate_unit_id' => $detail->alternate_unit_id,
                    'alternate_unit_name' => $detail->alternateUnit?->alter_unit,
                    'rate' => (float) $detail->rate,
                ];
            }) : [],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
