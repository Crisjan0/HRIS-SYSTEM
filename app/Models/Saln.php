<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saln extends Model
{
    protected $fillable = [
        'employee_id',
        'type_of_filing',
        'as_of_date',
        'declarant_info',
        'spouse_info',
        'filing_status',
        'children',
        'real_properties',
        'personal_properties',
        'liabilities',
        'has_business_interests',
        'business_interests',
        'has_relatives_in_gov',
        'relatives_in_gov',
        'total_assets',
        'total_liabilities',
        'net_worth',
    ];

    protected function casts(): array
    {
        return [
            'as_of_date' => 'date',
            'declarant_info' => 'array',
            'spouse_info' => 'array',
            'children' => 'array',
            'real_properties' => 'array',
            'personal_properties' => 'array',
            'liabilities' => 'array',
            'has_business_interests' => 'boolean',
            'business_interests' => 'array',
            'has_relatives_in_gov' => 'boolean',
            'relatives_in_gov' => 'array',
            'total_assets' => 'decimal:2',
            'total_liabilities' => 'decimal:2',
            'net_worth' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
