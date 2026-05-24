<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StaffQualification;
use App\Models\WorkExperience;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staff_number';
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'telephone_number',
        'date_of_birth',
        'sex',
        'nin',
        'department_id',
        'position',
        'current_salary',
        'salary_scale',
        'hours_per_week',
        'contract_type',
        'payment_type'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship with Qualifications
    public function qualifications()
    {
        return $this->hasMany(StaffQualification::class, 'staff_number', 'staff_number');
    }

    // Relationship with Work Experiences
    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class, 'staff_number', 'staff_number');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'staff_number', 'staff_number');
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'staff_number', 'staff_number');
    }
}