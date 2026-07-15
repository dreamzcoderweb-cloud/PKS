<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stock_id',
        'brand_name',
        'stock_name',
        'lott_number',
        'units',
        'mt',
        'stock_code',
        'branch_id',
        'unit_id',
        'alter_unit_id',
        'unit_value',
        'alter_unit_value',
        'created_by',
    ];

    /**
     * Get the user that created this stock.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function alternateUnit()
    {
        return $this->belongsTo(AlternateUnit::class, 'alter_unit_id', 'alter_unit_id');
    }
}
