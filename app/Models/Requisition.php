<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Requisition extends Model
{
    protected $primaryKey = 'requisition_number';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'requisition_number',
        'staff_number',
        'ward_number',
        'date_ordered',
    ];

    protected $casts = [
        'date_ordered' => 'date',
    ];


    public function staff(): BelongsTo
        {
            return $this->belongsTo(Staff::class, 'staff_number', 'staff_number');
        }

    public function ward(): BelongsTo
        {
            return $this->belongsTo(Ward::class, 'ward_number', 'ward_number');
        }

    // This requisition includes many surgical/non-medical items
    public function requisitionSupplyItems(): BelongsToMany
    {
        return $this->belongsToMany(
            SupplyItem::class,
            'requisition_supply_items',
            'requisition_number',
            'item_number'
        )->withPivot('quantity_required')->withTimestamps();
    }

    // This requisition includes many pharmaceutical/drug items
    public function requisitionDrugItems(): BelongsToMany
    {
        return $this->belongsToMany(
            PharmaceuticalItem::class,
            'requisition_drug_items',
            'requisition_number',
            'drug_number'
        )->withPivot('quantity_required')->withTimestamps();
    }
}
