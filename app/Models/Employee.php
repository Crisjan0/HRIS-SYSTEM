<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'suffix',
        'division',
        'contact_number',
        'role',
        'user_id',
        'rfid_number',
        'profile_picture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // PDS Relationships
    public function pdsPersonal()
    {
        return $this->hasOne(PdsPersonalInformation::class);
    }

    public function pdsFamily()
    {
        return $this->hasOne(PdsFamilyBackground::class);
    }

    public function pdsChildren()
    {
        return $this->hasMany(PdsChild::class);
    }

    public function pdsEducation()
    {
        return $this->hasMany(PdsEducation::class);
    }

    public function pdsEligibilities()
    {
        return $this->hasMany(PdsEligibility::class);
    }

    public function pdsWorkExperiences()
    {
        return $this->hasMany(PdsWorkExperience::class);
    }

    public function pdsVoluntaryWorks()
    {
        return $this->hasMany(PdsVoluntaryWork::class);
    }

    public function pdsTrainings()
    {
        return $this->hasMany(PdsTraining::class);
    }

    public function pdsOthers()
    {
        return $this->hasMany(PdsOtherInfo::class);
    }

    public function pdsQuestionnaire()
    {
        return $this->hasOne(PdsQuestionnaire::class);
    }

    public function pdsReferences()
    {
        return $this->hasMany(PdsReference::class);
    }

    public function pdsSectionReviews()
    {
        return $this->hasMany(PdsSectionReview::class);
    }

    public function pdsGovId()
    {
        return $this->hasOne(PdsGovernmentId::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveCredits()
    {
        return $this->hasMany(LeaveCredit::class);
    }

    public function salns(): HasMany
    {
        return $this->hasMany(Saln::class);
    }

    /**
     * Ensure the employee has leave credits for the given year.
     */
    public function ensureLeaveCredits(int $year): void
    {
        $types = LeaveType::where('is_active', true)->get();
        foreach ($types as $type) {
            $this->leaveCredits()->firstOrCreate(
                ['leave_type_id' => $type->id, 'year' => $year],
                ['balance' => $type->days_per_year ?? 0]
            );
        }
    }
}
