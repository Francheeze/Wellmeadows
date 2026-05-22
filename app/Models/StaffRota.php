<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRota extends Model
{
    protected $fillable = [
        'wardnumber', 'staffnumber', 'shift', 'weekstartdate'
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'wardnumber', 'wardnumber');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}