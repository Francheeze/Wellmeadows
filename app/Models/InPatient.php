<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InPatient extends Model
{
    // appointment_number is both PK and FK
    protected $primaryKey = 'appointment_number';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'appointment_number',
        'patient_number',
        'ward_number',
        'bed_number',
        'expected_stay',
        'date_placed',
        'date_leave',
        'actual_leave',
    ];

    protected $casts = [
        'date_placed'  => 'date',
        'date_leave'   => 'date',
        'actual_leave' => 'date',
        'expected_stay' => 'integer',
    ];

    // Belongs to its appointment
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_number', 'appointment_number');
    }

    // Belongs to a patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_number', 'patient_number');
    }

    // Belongs to a ward (another module)
    // Uncomment once Ward model is available:
    // public function ward(): BelongsTo
    // {
    //     return $this->belongsTo(Ward::class, 'ward_number', 'ward_number');
    // }

    // Belongs to a bed (another module)
    // Uncomment once Bed model is available:
    // public function bed(): BelongsTo
    // {
    //     return $this->belongsTo(Bed::class, 'bed_number', 'bed_number');
    // }

    // Helper: check if patient is currently admitted
    public function isCurrentlyAdmitted(): bool
    {
        return $this->actual_leave === null;
    }

    // Helper: calculate actual length of stay in days
    public function getLengthOfStayAttribute(): ?int
    {
        if (!$this->actual_leave) return null;
        return $this->date_placed->diffInDays($this->actual_leave);
    }
}
