<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $primaryKey = 'bed_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['bed_number', 'ward_number', 'status'];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_number', 'ward_number');
    }

    public function inPatient()
    {
        return $this->hasOne(InPatient::class, 'bed_number', 'bed_number');
    }
}