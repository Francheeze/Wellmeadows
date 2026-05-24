<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_number';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected $fillable = [
        'appointment_number',
        'patient_number',
        'staff_number',
        'date_time',
        'examination_room',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    // Belongs to a patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_number', 'patient_number');
    }

     public function staff(): BelongsTo
     {
         return $this->belongsTo(Staff::class, 'staffNumber', 'staff_number');
     }

    // An appointment has one exam result
    public function examResult(): HasOne
    {
        return $this->hasOne(ExamResult::class, 'appointment_number', 'appointment_number');
    }

    // An appointment may become an in-patient record
    public function inPatient(): HasOne
    {
        return $this->hasOne(InPatient::class, 'appointment_number', 'appointment_number');
    }

    // An appointment may become an out-patient record
    public function outPatient(): HasOne
    {
        return $this->hasOne(OutPatient::class, 'appointment_number', 'appointment_number');
    }

    // Helper: check if the appointment is upcoming
    public function isUpcoming(): bool
    {
        return $this->date_time->isFuture();
    }

    // Helper: check what the patient was classified as after exam
    public function getPatientTypeAttribute(): ?string
    {
        if ($this->inPatient()->exists())  return 'In-patient';
        if ($this->outPatient()->exists()) return 'Out-patient';
        return null;
    }
}
