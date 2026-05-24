<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $primaryKey = 'ward_number';
    public $incrementing = false;  
    protected $keyType = 'int';   

    protected $fillable = [
        'ward_number', 'ward_name', 'location',
        'total_beds', 'telephone_extention', 'charge_nurse_number'
    ];

    public function chargeNurse()
    {
        return $this->belongsTo(Staff::class, 'charge_nurse_number', 'staffNumber');
    }

    public function beds()
    {
        return $this->hasMany(Bed::class, 'ward_number', 'ward_number');
    }

    public function staffRotas()
    {
        return $this->hasMany(StaffRota::class, 'ward_number', 'ward_number');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'ward_number', 'ward_number');
    }

    public function inPatients()
    {
        return $this->hasMany(InPatient::class, 'ward_number', 'ward_number');
    }
}