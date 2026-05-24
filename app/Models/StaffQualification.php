<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffQualification extends Model
{
    use HasFactory;

    protected $primaryKey = 'qualification_id';
    protected $table = 'qualifications';

    protected $fillable = [
        'staff_number',
        'type',
        'date',
        'institution'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationship with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_number', 'staff_number');
    }
}