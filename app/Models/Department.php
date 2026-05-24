<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'department_id');
    }
}