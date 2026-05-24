<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NextOfKin extends Model
{
    protected $table = 'next_of_kins';
    protected $primaryKey = 'next_of_kin_id';

    protected $fillable = [
        'patient_number',
        'full_name',
        'relationship',
        'address',
        'telephone_number',
    ];

    // Belongs to a patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_number', 'patient_number');
    }
}
