<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_number';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'supplier_number',
        'supplier_name',
        'address',
        'telephone',
        'fax_number',
    ];

    // A supplier can supply many surgical/non-medical items
    public function surgicalItems(): HasMany
    {
        return $this->hasMany(SupplyItem::class, 'supplier_number', 'supplier_number');
    }

    // A supplier can supply many pharmaceutical items
    public function pharmaceuticalItems(): HasMany
    {
        return $this->hasMany(PharmaceuticalItem::class, 'supplier_number', 'supplier_number');
    }
}