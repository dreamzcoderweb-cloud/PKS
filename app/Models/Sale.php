<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'branch_id',
        'dealer_id',
        'lot_number',
        'transporter_id',
        'vehicle_id',
        'invoice_number',
        'driver_number',
        'sale_images',
        'created_by',
    ];

    protected $casts = [
        'sale_images' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class, 'transporter_id', 'transporter_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'id');
    }
}
