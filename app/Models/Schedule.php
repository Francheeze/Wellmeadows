<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'department',
        'start_time',
        'end_time',
    ];

    /**
     * Get the staff member associated with the schedule.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_number');
    }
}