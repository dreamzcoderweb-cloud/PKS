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
        'created_by',
    ];

    /**
     * Get the user that created this stock.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
