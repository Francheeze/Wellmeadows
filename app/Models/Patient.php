<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    protected $primaryKey = 'patient_number';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'patient_number',
        'first_name',
        'last_name',
        'address',
        'telephone_number',
        'date_of_birth',
        'sex',
        'marital_status',
        'date_registered',
        'referred_by',
    ];

    protected $casts = [
        'date_of_birth'   => 'date',
        'date_registered' => 'date',
    ];

    // Referred by a local doctor
    public function localDoctor(): BelongsTo
    {
        return $this->belongsTo(LocalDoctor::class, 'referred_by', 'clinic_number');
    }

    // A patient can have many next-of-kin records
    public function nextOfKins(): HasMany
    {
        return $this->hasMany(NextOfKin::class, 'patient_number', 'patient_number');
    }

    // A patient can have many appointments
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_number', 'patient_number');
    }

    // A patient can have many in-patient records
    public function inPatientRecords(): HasMany
    {
        return $this->hasMany(InPatient::class, 'patient_number', 'patient_number');
    }

    // A patient can have many out-patient records
    public function outPatientRecords(): HasMany
    {
        return $this->hasMany(OutPatient::class, 'patient_number', 'patient_number');
    }

    // Helper: get full name
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Helper: calculate age from date_of_birth
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
}
