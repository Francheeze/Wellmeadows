<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PharmaceuticalItem extends Model
{
    protected $primaryKey = 'drug_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'drug_number',
        'drug_name',
        'description',
        'dosage',
        'method_of_administration',
        'quantity_in_stock',
        'reorder_level',
        'cost_per_unit',
        'supplier_number',
    ];

    protected $casts = [
        'quantity_in_stock' => 'integer',
        'reorder_level'     => 'integer',
        'cost_per_unit'     => 'decimal:2',
    ];

    // This drug belongs to a supplier
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_number', 'supplier_number');
    }

    // This drug can be prescribed to many patients (via patient_medications)
    public function patientMedications(): HasMany
    {
        return $this->hasMany(PatientMedication::class, 'drug_number', 'drug_number');
    }

    // This drug can appear in many requisitions (via pivot)
    public function requisitions(): BelongsToMany
    {
        return $this->belongsToMany(
            Requisition::class,
            'requisition_drug_items',
            'drug_number',
            'requisition_number'
        )->withPivot('quantity_required')->withTimestamps();
    }

    // Helper: check if stock is at or below reorder level
    public function needsReorder(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }
}
