<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtrRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'am_out',
        'pm_in',
        'time_out',
        'status',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
