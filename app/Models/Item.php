<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SurgicalNoneItem extends Model
{
    protected $primaryKey = 'item_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'item_number',
        'item_name',
        'description',
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

    // This item belongs to a supplier
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_number', 'supplier_number');
    }

    // This item can appear in many requisitions (via pivot)
    public function requisitions(): BelongsToMany
    {
        return $this->belongsToMany(
            Requisition::class,
            'requisition_supply_items',
            'item_number',
            'requisition_number'
        )->withPivot('quantity_required')->withTimestamps();
    }

    // Helper: check if stock is at or below reorder level
    public function needsReorder(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }
}
