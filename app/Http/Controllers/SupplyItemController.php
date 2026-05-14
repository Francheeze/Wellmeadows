<?php

namespace App\Http\Controllers;

use App\Models\SupplyItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplyItemController extends Controller
{
    /**
     * Display a listing of all supply items.
     */
    public function index(Request $request): View
    {
        $query = SupplyItem::with('supplier');

        if ($request->filled('search')) {
            $query->where('item_name', 'ilike', "%{$request->search}%")
                  ->orWhere('item_number', 'ilike', "%{$request->search}%");
        }

        if ($request->stock_filter === 'low') {
            $query->whereColumn('quantity_in_stock', '<=', 'reorder_level');
        } elseif ($request->stock_filter === 'out') {
            $query->where('quantity_in_stock', 0);
        } elseif ($request->stock_filter === 'ok') {
            $query->whereColumn('quantity_in_stock', '>', 'reorder_level');
        }

        $sort = match($request->sort) {
            'item_number' => ['item_number', 'asc'],
            'cost'        => ['cost_per_unit', 'desc'],
            'stock'       => ['quantity_in_stock', 'asc'],
            default       => ['item_name', 'asc'],
        };
        $query->orderBy(...$sort);

        $supplyItems   = $query->paginate(15)->withQueryString();
        $lowStockCount = SupplyItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count();

        return view('supply_items.index', compact('supplyItems', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new supply item.
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('supply_items.create', compact('suppliers'));
    }

    /**
     * Store a newly created supply item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_number'       => ['required', 'integer', 'min:1', 'unique:supply_items,item_number'],
            'item_name'         => ['required', 'string', 'max:100'],
            'description'       => ['nullable', 'string'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'reorder_level'     => ['required', 'integer', 'min:0'],
            'cost_per_unit'     => ['required', 'numeric', 'min:0'],
            'supplier_number'   => ['required', 'exists:suppliers,supplier_number'],
        ]);

        SupplyItem::create($validated);

        return redirect()
            ->route('supply_items.index')
            ->with('success', 'Supply item created successfully.');
    }

    /**
     * Display the specified supply item along with its supplier and requisitions.
     */
    public function show(SupplyItem $supplyItem): View
    {
        $supplyItem->load([
            'supplier',
            'requisitions', // belongsToMany → Requisition via requisition_supply_items
        ]);

        return view('supply_items.show', compact('supplyItem'));
    }

    /**
     * Show the form for editing the specified supply item.
     */
    public function edit(SupplyItem $supplyItem): View
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('supply_items.edit', compact('supplyItem', 'suppliers'));
    }

    /**
     * Update the specified supply item in storage.
     */
    public function update(Request $request, SupplyItem $supplyItem): RedirectResponse
    {
        $validated = $request->validate([
            'item_name'         => ['required', 'string', 'max:100'],
            'description'       => ['nullable', 'string'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'reorder_level'     => ['required', 'integer', 'min:0'],
            'cost_per_unit'     => ['required', 'numeric', 'min:0'],
            'supplier_number'   => ['required', 'integer', 'exists:suppliers,supplier_number'],
        ]);

        $supplyItem->update($validated);

        return redirect()
            ->route('supply_items.index')
            ->with('success', 'Supply item updated successfully.');
    }

    /**
     * Remove the specified supply item from storage.
     */
    public function destroy(SupplyItem $supplyItem): RedirectResponse
    {
        $supplyItem->delete();

        return redirect()
            ->route('supply_items.index')
            ->with('success', 'Supply item deleted successfully.');
    }
}