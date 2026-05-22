<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $primaryKey = 'wardnumber';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'wardnumber', 'wardname', 'location',
        'total_beds', 'telephone_extension', 'chargeursery_number'
    ];

    public function beds()
    {
        return $this->hasMany(Bed::class, 'wardnumber', 'wardnumber');
    }

    public function staffRota()
    {
        return $this->hasMany(StaffRota::class, 'wardnumber', 'wardnumber');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'wardnumber', 'wardnumber');
    }

    public function inPatients()
    {
        return $this->hasMany(InPatient::class, 'wardnumber', 'wardnumber');
    }
}