<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'position',
        'currentSalary',
        'salaryScale',
        'hoursPerWeek',
        'contractType',
        'paymentType'
    ];

    // Relationship with Qualifications
    public function qualifications()
    {
        return $this->hasMany(Qualification::class, 'staffNumber', 'staffNumber');
    }

    // Relationship with Work Experiences
    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class, 'staffNumber', 'staffNumber');
    }
}