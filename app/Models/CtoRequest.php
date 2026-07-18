<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtoRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'date_start',
        'date_end',
        'hours',
        'purpose',
        'attachment_path',
        'applicant_signature_path',
        'cto_balance_before',
        'cto_balance_after',
        'status',
        'approved_by_chief',
        'chief_status',
        'chief_remarks',
        'approved_by_hrstaff',
        'hrstaff_status',
        'hrstaff_remarks',
        'approved_by_regionaldirector',
        'rd_status',
        'rd_remarks',
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
            'hours' => 'decimal:2',
            'cto_balance_before' => 'decimal:2',
            'cto_balance_after' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function chief(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by_chief');
    }

    public function hrstaff(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by_hrstaff');
    }

    public function regionalDirector(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by_regionaldirector');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'earn' => 'Earn CTO',
            'use' => 'Use CTO',
            default => ucfirst($this->type),
        };
    }
}
