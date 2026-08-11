<?php

namespace App\Http\Resources\Mob\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMobPurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'purchase_id' => $this->purchase_id,
            'branch_id' => $this->branch_id,
            'branch_name' => $this->branch?->name,
            'dealer_id' => $this->dealer_id,
            'dealer_name' => $this->dealer?->name,
            'lot_number' => $this->lot_number,
            'transporter_id' => $this->transporter_id,
            'transporter_name' => $this->transporter?->name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle_number' => $this->vehicle?->name,
            'driver_number' => $this->driver_number,
            'purchase_images' => array_map(function ($image) {
                return filter_var($image, FILTER_VALIDATE_URL) ? $image : asset($image);
            }, $this->purchase_images ?? []),
            'created_by' => $this->created_by,
            'creator_name' => $this->user?->name,
            'details' => $this->details ? $this->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'brand_name' => $detail->brand_name,
                    'stock_name' => $detail->stock_name,
                    'lot_number' => $detail->lot_number,
                    'unit_value' => (float) $detail->unit_value,
                    'unit_type' => $detail->unit_type,
                    'alter_unit_value' => (float) $detail->alter_unit_value,
                    'alter_unit_type' => $detail->alter_unit_type,
                    'rate' => (float) $detail->rate,
                ];
            }) : [],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
