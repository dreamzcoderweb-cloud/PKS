<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchPrice extends Model
{
    use HasFactory;

    protected $table = 'branch_prices';

    protected $fillable = [
        'branch_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}
