<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternateUnit extends Model
{
    use HasFactory;

    protected $table = 'alternate_units';

    protected $primaryKey = 'alter_unit_id';

    protected $fillable = [
        'alter_unit',
    ];
}
