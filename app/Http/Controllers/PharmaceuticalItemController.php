<?php

namespace App\Http\Controllers;

use App\Models\PharmaceuticalItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PharmaceuticalItemController extends Controller
{
    /**
     * Display a listing of all pharmaceutical items.
     */
    public function index(Request $request)
    {
        $query = PharmaceuticalItem::with('supplier');

        if ($request->filled('search')) {
            $query->where('drug_name', 'ilike', "%{$request->search}%")
                  ->orWhere('drug_number', 'ilike', "%{$request->search}%");
        }

        if ($request->stock_filter === 'low') {
            $query->whereColumn('quantity_in_stock', '<=', 'reorder_level');
        } elseif ($request->stock_filter === 'ok') {
            $query->whereColumn('quantity_in_stock', '>', 'reorder_level');
        }

        $sort = match($request->sort) {
            'drug_number' => ['drug_number', 'asc'],
            'cost'        => ['cost_per_unit', 'desc'],
            'stock'       => ['quantity_in_stock', 'asc'],
            default       => ['drug_name', 'asc'],
        };
        $query->orderBy(...$sort);

        $pharmaceuticalItems = $query->paginate(15)->withQueryString();
        $lowStockCount = PharmaceuticalItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count();

        return view('pharmaceutical_items.index', compact('pharmaceuticalItems', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new pharmaceutical item.
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('pharmaceutical_items.create', compact('suppliers'));
    }

    /**
     * Store a newly created pharmaceutical item in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'drug_number'             => [
                'required',
                'integer',
                'min:1',
                'unique:pharmaceutical_items,drug_number',
            ],
            'drug_name'               => ['required', 'string', 'max:100'],
            'description'             => ['nullable', 'string', 'max:255'],
            'dosage'                  => ['nullable', 'string', 'max:100'],
            'method_of_administration'=> ['nullable', 'string', 'max:100'],
            'quantity_in_stock'       => ['required', 'integer', 'min:0'],
            'reorder_level'           => ['required', 'integer', 'min:0'],
            'cost_per_unit'           => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'supplier_number'         => ['required', 'integer', 'exists:suppliers,supplier_number'],
        ]);

        PharmaceuticalItem::create($validated);

        return redirect()
            ->route('pharmaceutical_items.index')
            ->with('success', 'Pharmaceutical item added successfully.');
    }

    /**
     * Display the specified pharmaceutical item.
     */
    public function show(PharmaceuticalItem $pharmaceuticalItem): View
    {
        // 'requisitions' is a belongsToMany through the requisition_drug_items pivot.
        // 'patientMedications' is a belongsToMany through the patient_medication pivot
        // (or a hasMany if PatientMedication has its own model — adjust to match yours).
        $pharmaceuticalItem->load([
            'supplier',
            'requisitions',         // belongsToMany → Requisition via requisition_drug_items
            'patientMedications',   // adjust relationship name to match your PharmaceuticalItem model
        ]);

        return view('pharmaceutical_items.show', compact('pharmaceuticalItem'));
    }

    /**
     * Show the form for editing the specified pharmaceutical item.
     */
    public function edit(PharmaceuticalItem $pharmaceuticalItem): View
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('pharmaceutical_items.edit', compact('pharmaceuticalItem', 'suppliers'));
    }

    /**
     * Update the specified pharmaceutical item in the database.
     */
    public function update(Request $request, PharmaceuticalItem $pharmaceuticalItem): RedirectResponse
    {
        $validated = $request->validate([
            'drug_name'               => ['required', 'string', 'max:100'],
            'description'             => ['nullable', 'string', 'max:255'],
            'dosage'                  => ['nullable', 'string', 'max:100'],
            'method_of_administration'=> ['nullable', 'string', 'max:100'],
            'quantity_in_stock'       => ['required', 'integer', 'min:0'],
            'reorder_level'           => ['required', 'integer', 'min:0'],
            'cost_per_unit'           => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'supplier_number'         => ['required', 'integer', 'exists:suppliers,supplier_number'],
        ]);

        $pharmaceuticalItem->update($validated);

        return redirect()
            ->route('pharmaceutical_items.show', $pharmaceuticalItem->drug_number)
            ->with('success', 'Pharmaceutical item updated successfully.');
    }

    /**
     * Remove the specified pharmaceutical item from the database.
     */
    public function destroy(PharmaceuticalItem $pharmaceuticalItem): RedirectResponse
    {
        $pharmaceuticalItem->delete();

        return redirect()
            ->route('pharmaceutical_items.index')
            ->with('success', 'Pharmaceutical item deleted successfully.');
    }
}