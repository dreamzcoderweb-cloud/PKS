<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'name',
        'branch_name',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $with = ['branchPrice'];

    protected $priceValue;

    public function setNameAttribute($value)
    {
        $this->attributes['branch_name'] = $value;
    }

    public function getNameAttribute()
    {
        return $this->attributes['branch_name'] ?? null;
    }

    public function setPriceAttribute($value)
    {
        $this->priceValue = $value;
    }

    public function getPriceAttribute()
    {
        $val = null;
        if ($this->priceValue !== null) {
            $val = $this->priceValue;
        } else {
            $val = $this->branchPrice?->price;
        }
        return $val !== null ? number_format((float) $val, 2, '.', '') : null;
    }

    public function branchPrice()
    {
        return $this->hasOne(BranchPrice::class, 'branch_id', 'branch_id');
    }

    protected static function booted()
    {
        static::saved(function ($branch) {
            if ($branch->priceValue !== null) {
                $branch->branchPrice()->updateOrCreate(
                    ['branch_id' => $branch->branch_id],
                    ['price' => $branch->priceValue]
                );
            }
        });
    }
}
