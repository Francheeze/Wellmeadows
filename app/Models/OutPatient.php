<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutPatient extends Model
{
    // appointment_number is both PK and FK
    protected $primaryKey = 'appointment_number';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'appointment_number',
        'patient_number',
        'appointment_date_time',
    ];

    protected $casts = [
        'appointment_date_time' => 'datetime',
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
}
