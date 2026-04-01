<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'date_filed',
        'reason',
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
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function chief()
    {
        return $this->belongsTo(Employee::class, 'approved_by_chief');
    }

    public function hrstaff()
    {
        return $this->belongsTo(Employee::class, 'approved_by_hrstaff');
    }

    public function regionalDirector()
    {
        return $this->belongsTo(Employee::class, 'approved_by_regionaldirector');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'date_filed' => 'datetime',
        ];
    }
}
