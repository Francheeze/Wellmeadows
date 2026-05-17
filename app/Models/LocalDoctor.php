<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocalDoctor extends Model
{
    protected $primaryKey = 'clinic_number';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'clinic_number',
        'full_name',
        'address',
        'telephone_number',
    ];

    // A local doctor can refer many patients
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'referred_by', 'clinic_number');
    }
}
