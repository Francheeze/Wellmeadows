<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $primaryKey = 'bednumber';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['bednumber', 'wardnumber', 'status'];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'wardnumber', 'wardnumber');
    }
}