<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    protected $table = 'staff';
    protected $primaryKey = 'staffNumber';
    protected $guarded = []; // Allows all fields to be saved

    public function qualifications() {
        return $this->hasMany(StaffQualification::class, 'staffNumber', 'staffNumber');
    }

    public function experiences() {
        return $this->hasMany(WorkExperience::class, 'staffNumber', 'staffNumber');
    }
}