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
            if (empty($staff->staff_number)) {
                $lastStaff = static::orderBy('staff_number', 'desc')->first();

                if (!$lastStaff) {
                    $nextNumber = 1;
                } else {
                    $lastNumber = intval(substr($lastStaff->staff_number, 1));
                    $nextNumber = $lastNumber + 1;
                }

                $staff->staff_number = 'S' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'department_id' => 'integer',
    ];

    public function getForeignKey()
    {
        return 'staff_number';
    }

    // Manually resolve department via accessor to bypass eager loading bug
    // with non-incrementing string primary keys in Laravel 12
    public function getDepartmentAttribute()
    {
        if (array_key_exists('department', $this->relations)) {
            return $this->relations['department'];
        }
        $department = Department::find($this->department_id);
        $this->setRelation('department', $department);
        return $department;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function qualifications()
    {
        return $this->hasMany(StaffQualification::class, 'staff_number', 'staff_number');
    }

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