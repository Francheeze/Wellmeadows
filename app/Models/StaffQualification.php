<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
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

    // Relationship with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staffNumber', 'staffNumber');
    }
}