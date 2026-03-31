<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsGovernmentId extends Model
{
    use HasFactory;

    protected $table = 'pds_government_ids';
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
