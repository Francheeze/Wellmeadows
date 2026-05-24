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
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staff_number',
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
        'payment_type',
    ];

    protected static function booted()
    {
        static::creating(function ($staff) {
            // If no staff_number is provided, generate the next one
            if (empty($staff->staff_number)) {
                $lastStaff = static::orderBy('staff_number', 'desc')->first();
                
                if (!$lastStaff) {
                    // First staff member: s001
                    $nextNumber = 1;
                } else {
                    // Extract the numeric part and increment
                    $lastNumber = intval(substr($lastStaff->staff_number, 1));
                    $nextNumber = $lastNumber + 1;
                }
                
                // Format with leading zeros: S001, S002, etc.
                $staff->staff_number = 'S' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'date_of_birth' => 'date',
         'department_id' => 'integer', 
    ];

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

    // Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}