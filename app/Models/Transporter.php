<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporter extends Model
{
    use HasFactory;

    protected $table = 'transporters';

    protected $primaryKey = 'transporter_id';

    protected $fillable = [
        'name',
        'branch_id',
    ];

    /**
     * Get the branch associated with the transporter.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}
