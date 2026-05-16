<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffQualification extends Model
{
    use HasFactory;

    protected $primaryKey = 'qualificationID';
    protected $table = 'qualifications';
    
    protected $fillable = [
        'staffNumber',
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
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}