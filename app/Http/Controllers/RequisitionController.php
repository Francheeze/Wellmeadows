<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\PharmaceuticalItem;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RequisitionController extends Controller
{
    /**
     * Display a listing of all requisitions.
     */
    public function index(Request $request): View
    {
        $query = Requisition::with(['requisitionDrugItems', 'requisitionSupplyItems']);

        if ($request->filled('search')) {
            $query->where('requisition_number', 'ilike', "%{$request->search}%")
                  ->orWhere('staff_number', 'ilike', "%{$request->search}%")
                  ->orWhere('ward_number', 'ilike', "%{$request->search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_ordered', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_ordered', '<=', $request->date_to);
        }

        $requisitions = $query->orderBy('date_ordered', 'desc')->paginate(15)->withQueryString();

        $todayCount       = Requisition::whereDate('date_ordered', today())->count();
        $drugItemsCount   = \Illuminate\Support\Facades\DB::table('requisition_drug_items')->count();
        $supplyItemsCount = \Illuminate\Support\Facades\DB::table('requisition_supply_items')->count();

        return view('requisitions.index', compact('requisitions', 'todayCount', 'drugItemsCount', 'supplyItemsCount'));
    }

    /**
     * Show the form for creating a new requisition.
     */
    public function create(): View
    {
        $pharmaceuticalItems = PharmaceuticalItem::orderBy('drug_name')->get();
        $supplyItems         = SupplyItem::orderBy('item_name')->get();

        return view('requisitions.create', compact('pharmaceuticalItems', 'supplyItems'));
    }

    /**
     * Store a newly created requisition in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'requisition_number' => ['required', 'integer', 'unique:requisitions,requisition_number'],
            'staff_number'       => ['required', 'string', 'max:50', 'exists:staff,staffNumber'],
            'ward_number'        => ['required', 'integer', 'exists:wards,ward_number'],
            'date_ordered'       => ['required', 'date'],

            // Drug items (optional — requisition may have only supply items)
            'drug_items'                       => ['nullable', 'array'],
            'drug_items.*.drug_number'         => ['required_with:drug_items', 'exists:pharmaceutical_items,drug_number'],
            'drug_items.*.quantity_required'   => ['required_with:drug_items', 'integer', 'min:1'],

            // Supply items (optional — requisition may have only drug items)
            'supply_items'                     => ['nullable', 'array'],
            'supply_items.*.item_number'       => ['required_with:supply_items', 'exists:supply_items,item_number'],
            'supply_items.*.quantity_required' => ['required_with:supply_items', 'integer', 'min:1'],
        ]);

        // Require at least one item type
        if (empty($request->drug_items) && empty($request->supply_items)) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'A requisition must include at least one drug item or supply item.']);
        }

        // Create the requisition
        $requisition = Requisition::create([
            'requisition_number' => $validated['requisition_number'],
            'staff_number'       => $validated['staff_number'],
            'ward_number'        => $validated['ward_number'],
            'date_ordered'       => $validated['date_ordered'],
        ]);

        // Attach drug items to pivot
        if (!empty($validated['drug_items'])) {
            $drugPivot = [];
            foreach ($validated['drug_items'] as $drug) {
                $drugPivot[$drug['drug_number']] = ['quantity_required' => $drug['quantity_required']];
            }
            $requisition->requisitionDrugItems()->sync($drugPivot);
        }

        // Attach supply items to pivot
        if (!empty($validated['supply_items'])) {
            $supplyPivot = [];
            foreach ($validated['supply_items'] as $item) {
                $supplyPivot[$item['item_number']] = ['quantity_required' => $item['quantity_required']];
            }
            $requisition->requisitionSupplyItems()->sync($supplyPivot);
        }

        return redirect()
            ->route('requisitions.show', $requisition->requisition_number)
            ->with('success', 'Requisition created successfully.');
    }

    /**
     * Display the specified requisition with its items.
     */
    public function show(Requisition $requisition): View
    {
        $requisition->load([
            'requisitionDrugItems',
            'requisitionSupplyItems',
        ]);

        return view('requisitions.show', compact('requisition'));
    }

    /**
     * Show the form for editing the specified requisition.
     */
    public function edit(Requisition $requisition): View
    {
        $requisition->load([
            'requisitionDrugItems',
            'requisitionSupplyItems',
        ]);

        $pharmaceuticalItems = PharmaceuticalItem::orderBy('drug_name')->get();
        $supplyItems         = SupplyItem::orderBy('item_name')->get();

        return view('requisitions.edit', compact(
            'requisition',
            'pharmaceuticalItems',
            'supplyItems'
        ));
    }

    /**
     * Update the specified requisition in storage.
     */
    public function update(Request $request, Requisition $requisition): RedirectResponse
    {
        $validated = $request->validate([
            'staff_number'  => ['required', 'string', 'max:50', 'exists:staff,staffNumber'],
            'ward_number'   => ['required', 'integer', 'exists:wards,ward_number'],
            'date_ordered'  => ['required', 'date'],

            'drug_items'                       => ['nullable', 'array'],
            'drug_items.*.drug_number'         => ['required_with:drug_items', 'exists:pharmaceutical_items,drug_number'],
            'drug_items.*.quantity_required'   => ['required_with:drug_items', 'integer', 'min:1'],

            'supply_items'                     => ['nullable', 'array'],
            'supply_items.*.item_number'       => ['required_with:supply_items', 'exists:supply_items,item_number'],
            'supply_items.*.quantity_required' => ['required_with:supply_items', 'integer', 'min:1'],
        ]);

        if (empty($request->drug_items) && empty($request->supply_items)) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'A requisition must include at least one drug item or supply item.']);
        }

        // Update core fields
        $requisition->update([
            'staff_number' => $validated['staff_number'],
            'ward_number'  => $validated['ward_number'],
            'date_ordered' => $validated['date_ordered'],
        ]);

        // Re-sync drug items
        $drugPivot = [];
        if (!empty($validated['drug_items'])) {
            foreach ($validated['drug_items'] as $drug) {
                $drugPivot[$drug['drug_number']] = ['quantity_required' => $drug['quantity_required']];
            }
        }
        $requisition->requisitionDrugItems()->sync($drugPivot);

        // Re-sync supply items
        $supplyPivot = [];
        if (!empty($validated['supply_items'])) {
            foreach ($validated['supply_items'] as $item) {
                $supplyPivot[$item['item_number']] = ['quantity_required' => $item['quantity_required']];
            }
        }
        $requisition->requisitionSupplyItems()->sync($supplyPivot);

        return redirect()
            ->route('requisitions.show', $requisition->requisition_number)
            ->with('success', 'Requisition updated successfully.');
    }

    /**
     * Remove the specified requisition from storage.
     */
    public function destroy(Requisition $requisition): RedirectResponse
    {
        // Detach pivot records first to avoid orphaned rows
        $requisition->requisitionDrugItems()->detach();
        $requisition->requisitionSupplyItems()->detach();

        $requisition->delete();

        return redirect()
            ->route('requisitions.index')
            ->with('success', 'Requisition deleted successfully.');
    }
}