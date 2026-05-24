<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRota extends Model
{
    protected $fillable = [
        'ward_number', 'staff_number', 'shift', 'week_start_date'
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_number', 'ward_number');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_number', 'staffNumber');
    }
}