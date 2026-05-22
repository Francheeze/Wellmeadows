<?php

namespace App\Http\Controllers;

use App\Models\LocalDoctor;
use Illuminate\Http\Request;

class LocalDoctorController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all local doctors
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = LocalDoctor::withCount('patients');

        // Search by name, clinic number, or telephone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('clinic_number',    'ilike', "%{$search}%")
                  ->orWhere('full_name',       'ilike', "%{$search}%")
                  ->orWhere('telephone_number','ilike', "%{$search}%")
                  ->orWhere('address',         'ilike', "%{$search}%");
            });
        }

        $doctors    = $query->orderBy('full_name')->paginate(15)->withQueryString();
        $totalCount = LocalDoctor::count();

        return view('local_doctors.index', compact('doctors', 'totalCount'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        return view('local_doctors.create');
    }

    // ──────────────────────────────────────────────
    // STORE new local doctor
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_number'    => 'required|string|max:20|unique:local_doctors,clinic_number',
            'full_name'        => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
        ]);

        LocalDoctor::create($validated);

        return redirect()
            ->route('local_doctors.index')
            ->with('success', 'Local doctor added successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single local doctor + their referred patients
    // ──────────────────────────────────────────────
    public function show(LocalDoctor $localDoctor)
    {
        $localDoctor->load(['patients' => function ($q) {
            $q->orderBy('last_name')->paginate(10);
        }]);

        $referredPatients = $localDoctor->patients()
            ->orderBy('last_name')
            ->paginate(10);

        return view('local_doctors.show', compact('localDoctor', 'referredPatients'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(LocalDoctor $localDoctor)
    {
        return view('local_doctors.edit', compact('localDoctor'));
    }

    // ──────────────────────────────────────────────
    // UPDATE local doctor
    // clinic_number is immutable after creation
    // ──────────────────────────────────────────────
    public function update(Request $request, LocalDoctor $localDoctor)
    {
        $validated = $request->validate([
            'full_name'        => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
        ]);

        $localDoctor->update($validated);

        return redirect()
            ->route('local_doctors.index')
            ->with('success', 'Local doctor record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE local doctor
    // Safe to delete even if patients reference them —
    // referred_by is SET NULL on delete (set in migration),
    // so patients remain but lose the referring doctor link
    // ──────────────────────────────────────────────
    public function destroy(LocalDoctor $localDoctor)
    {
        $referredCount = $localDoctor->patients()->count();

        $localDoctor->delete();

        $message = $referredCount > 0
            ? "Local doctor deleted. {$referredCount} patient(s) had their referral reference cleared."
            : 'Local doctor deleted successfully.';

        return redirect()
            ->route('local_doctors.index')
            ->with('success', $message);
    }
}