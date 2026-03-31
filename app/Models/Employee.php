<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'role',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // PDS Relationships
    public function pdsPersonal() { return $this->hasOne(PdsPersonalInformation::class); }
    public function pdsFamily() { return $this->hasOne(PdsFamilyBackground::class); }
    public function pdsChildren() { return $this->hasMany(PdsChild::class); }
    public function pdsEducation() { return $this->hasMany(PdsEducation::class); }
    public function pdsEligibilities() { return $this->hasMany(PdsEligibility::class); }
    public function pdsWorkExperiences() { return $this->hasMany(PdsWorkExperience::class); }
    public function pdsVoluntaryWorks() { return $this->hasMany(PdsVoluntaryWork::class); }
    public function pdsTrainings() { return $this->hasMany(PdsTraining::class); }
    public function pdsOthers() { return $this->hasMany(PdsOtherInfo::class); }
    public function pdsQuestionnaire() { return $this->hasOne(PdsQuestionnaire::class); }
    public function pdsReferences() { return $this->hasMany(PdsReference::class); }
    public function pdsGovId() { return $this->hasOne(PdsGovernmentId::class); }
}
