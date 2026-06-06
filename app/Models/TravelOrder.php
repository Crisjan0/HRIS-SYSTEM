<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TravelOrder extends Model
{
    protected $fillable = [
        'employee_id',
        'travel_type',
        'travel_date_start',
        'travel_date_end',
        'places_of_travel',
        'purpose',
        'attachment_path',
        'status',
        'approved_by_chief',
        'chief_status',
        'chief_remarks',
        'approved_by_regionaldirector',
        'rd_status',
        'rd_remarks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'travel_date_start' => 'date',
            'travel_date_end' => 'date',
        ];
    }

    /**
     * Get the employee who created this travel order.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the chief who approved/rejected this travel order.
     */
    public function chief(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by_chief');
    }

    /**
     * Get the regional director who approved/rejected this travel order.
     */
    public function regionalDirector(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by_regionaldirector');
    }

    /**
     * Get the companion employees for this travel order.
     */
    public function companions(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'travel_order_companions')
            ->withTimestamps();
    }

    /**
     * Get a human-readable label for the travel type.
     */
    public function getTravelTypeLabelAttribute(): string
    {
        return match ($this->travel_type) {
            'local' => 'Local',
            'foreign' => 'Foreign',
            'official_business' => 'Official Business',
            default => ucfirst($this->travel_type),
        };
    }
}
