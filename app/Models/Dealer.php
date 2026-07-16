<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'dealer_code',
        'branch_id',
        'name',
        'business_name',
        'contact_number',
        'address',
        'status',
        'created_by',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
