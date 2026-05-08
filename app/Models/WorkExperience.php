<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model {
    protected $primaryKey = 'workExperienceID';
    protected $guarded = [];

    public function staff() {
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}
