<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_SALE = 0;
    public const TYPE_DECORTICATE = 1;
    public const TYPE_CASH_SALE = 2;

    public const TYPES = [
        self::TYPE_SALE => 'Sale',
        self::TYPE_DECORTICATE => 'Decorticate',
        self::TYPE_CASH_SALE => 'Cash Sale',
    ];

    public static function getSaleTypeText(?int $type): string
    {
        return self::TYPES[(int) ($type ?? 0)] ?? 'Sale';
    }

    public static function parseSaleType(mixed $value): int
    {
        if (is_numeric($value)) {
            $intVal = (int) $value;
            if (array_key_exists($intVal, self::TYPES)) {
                return $intVal;
            }
            return self::TYPE_SALE;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));
            if ($normalized === 'decorticate') {
                return self::TYPE_DECORTICATE;
            }
            if (in_array($normalized, ['cash sale', 'cash_sale', 'cashsale', 'cash'])) {
                return self::TYPE_CASH_SALE;
            }
            if ($normalized === 'sale') {
                return self::TYPE_SALE;
            }
        }

        return self::TYPE_SALE;
    }

    protected $fillable = [
        'sale_id',
        'branch_id',
        'dealer_id',
        'vehicle_id',
        'saletype',
        'invoice_number',
        'driver_name',
        'driver_number',
        'sale_date',
        'sale_images',
        'created_by',
    ];

    protected $casts = [
        'sale_images' => 'array',
        'sale_date' => 'datetime',
        'saletype' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
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
