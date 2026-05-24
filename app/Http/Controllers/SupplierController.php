<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of all suppliers.
     */
    public function index(Request $request): View
    {
        $query = Supplier::orderBy('supplier_number', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'ilike', "%{$search}%")
                ->orWhere('address',     'ilike', "%{$search}%")
                ->orWhere('telephone',   'ilike', "%{$search}%");
            });
        }

        $suppliers = $query->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_number' => ['required', 'integer', 'unique:suppliers,supplier_number'],
            'supplier_name'   => ['required', 'string', 'max:100'],
            'address'        => ['required', 'string', 'max:255'],
            'telephone'      => ['required', 'string', 'max:20'],
            'fax_number'      => ['nullable', 'string', 'max:20'],
        ]);

        Supplier::create([
            'supplier_number' => $validated['supplier_number'],
            'supplier_name'   => $validated['supplier_name'],
            'address'         => $validated['address'],
            'telephone'       => $validated['telephone'],
            'fax_number'      => $validated['fax_number'] ?? null,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier): View
    {
        // Eager-load related supply items and pharmaceutical items via the supplier FK
        $supplier->load([
            'surgicalItems',
            'pharmaceuticalItems',
        ]);

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_name' => ['required', 'string', 'max:100'],
            'address'      => ['required', 'string', 'max:255'],
            'telephone'    => ['required', 'string', 'max:20'],
            'fax_number'    => ['nullable', 'string', 'max:20'],
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        // Guard: prevent deletion if supplier still has linked items
        if ($supplier->surgicalItems()->exists() || $supplier->pharmaceuticalItems()->exists()) {
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Cannot delete supplier. It still has linked items.');
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}