<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    // appointment_number is both PK and FK (1-to-1 with Appointment)
    protected $primaryKey = 'appointment_number';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'appointment_number',
        'result',
        'examined_date',
    ];

    protected $casts = [
        'examined_date' => 'date',
    ];

    // Belongs to its appointment
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_number', 'appointment_number');
    }

    // Helper: check if patient was admitted
    public function isAdmitted(): bool
    {
        return $this->result === 'WaitingList';
    }
}
