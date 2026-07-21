<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatepass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gatepasses';

    protected $fillable = [
        'gatepass_number',
        'gatepass_type',
        'movement_type',
        'sale_id',
        'purchase_id',
        'branch_id',
        'dealer_id',
        'customer_id',
        'transporter_id',
        'vehicle_id',
        'driver_name',
        'driver_number',
        'gatepass_date',
        'gatepass_images',
        'remarks',
        'status',
        'created_by',
    ];

    protected $casts = [
        'gatepass_images' => 'array',
        'gatepass_date' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class, 'transporter_id', 'transporter_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(GatepassDetail::class, 'gatepass_id', 'id');
    }
}
