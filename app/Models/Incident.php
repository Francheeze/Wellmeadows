<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'incident_type',
        'description',
        'incident_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'incident_date' => 'date',
    ];

    /**
     * Get the staff member associated with the incident.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_number');
    }
}