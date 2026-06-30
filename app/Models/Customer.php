<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_code',
        'name',
        'business',
        'mobile',
        'address',
        'location',
        'gst_number',
        'status',
        'added_by',
    ];

    /**
     * Get the user that added this customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
