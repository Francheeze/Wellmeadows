<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffQualification extends Model {
    protected $primaryKey = 'qualificationID';
    protected $guarded = [];

    public function staff() {
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}