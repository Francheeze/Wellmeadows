<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StaffQualification;
use App\Models\WorkExperience;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staffNumber';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'firstName',
        'lastName',
        'address',
        'telephoneNumber',
        'dateOfBirth',
        'sex',
        'NIN',
        'department',
        'position',
        'currentSalary',
        'salaryScale',
        'hoursPerWeek',
        'contractType',
        'paymentType'
    ];

    protected $casts = [
        'dateOfBirth' => 'date',
    ];

    // Relationship with Qualifications
    public function qualifications()
    {
        return $this->hasMany(StaffQualification::class, 'staffNumber', 'staffNumber');
    }

    // Relationship with Work Experiences
    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class, 'staffNumber', 'staffNumber');
    }
}