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
        'totalbeds', 'telephoneextention', 'chargenursenumber'
    ];

    public function beds()
    {
        return $this->hasMany(Bed::class, 'wardnumber', 'wardnumber');
    }

    public function staffRota()
    {
        return $this->hasMany(StaffRota::class, 'wardnumber', 'wardnumber');
    }
}