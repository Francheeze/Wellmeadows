<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientMedication extends Model
{
    // Composite primary key — Laravel doesn't natively support composite PKs,
    // so we disable the default incrementing key and handle it manually.
    public $incrementing = false;
    protected $primaryKey = null; // Managed via composite unique constraint in migration

    protected $fillable = [
        'patient_number',
        'drug_number',
        'units_per_day',
        'start_date',
        'finish_date',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'finish_date'   => 'date',
        'units_per_day' => 'integer',
    ];

    // Belongs to a patient (another module's table)
    // Uncomment once the Patient model is available from that module:
    // public function patient(): BelongsTo
    // {
    //     return $this->belongsTo(Patient::class, 'patient_number', 'patient_number');
    // }

    // Belongs to a pharmaceutical item
    public function pharmaceuticalItem(): BelongsTo
    {
        return $this->belongsTo(PharmaceuticalItem::class, 'drug_number', 'drug_number');
    }

    // Helper: check if the medication course is still active
    public function isActive(): bool
    {
        $today = now()->toDateString();
        return $this->start_date <= $today &&
               ($this->finish_date === null || $this->finish_date >= $today);
    }
}