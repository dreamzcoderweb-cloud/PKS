<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_code',
        'name',
        'email',
        'mobile_number',
        'password',
        'branch_id',
        'business',
        'address',
        'location',
        'gst_number',
        'status',
        'added_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => 'integer',
        ];
    }

    /**
     * Get the user that added this customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the branch associated with the customer.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    /**
     * Get the virtual role attribute.
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        return 'user';
    }

    /**
     * Get the ID of the user owning/creating records.
     *
     * @return int
     */
    public function getOwnerId(): int
    {
        return (int) $this->added_by;
    }
}
