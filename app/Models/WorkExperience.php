<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_experience_id';
    protected $table = 'work_experiences';

    protected $fillable = [
        'staff_number',
        'position',
        'organization',
        'start_date',
        'finish_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
    ];

    // Relationship with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_number', 'staff_number');
    }
}