<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'stock_id',
        'lot_number',
        'unit_value',
        'unit_id',
        'alternate_unit_value',
        'alternate_unit_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function alternateUnit()
    {
        return $this->belongsTo(AlternateUnit::class, 'alternate_unit_id', 'alter_unit_id');
    }
}
