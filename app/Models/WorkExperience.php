<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $primaryKey = 'workExperienceID';
    protected $table = 'work_experiences';
    
    protected $fillable = [
        'staffNumber',
        'position',
        'organization',
        'startDate',
        'finishDate'
    ];

    // Relationship with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}