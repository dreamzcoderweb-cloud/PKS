<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'vehicle_type',
        'name',
        'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
