<?php

namespace App\Http\Controllers;

use App\Models\PatientMedication;
use App\Models\PharmaceuticalItem;
use Illuminate\Http\Request;

class PatientMedicationController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all patient medication records
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = PatientMedication::with('pharmaceuticalItem');

        // Search by patient number or drug name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_number', 'ilike', "%{$search}%")
                  ->orWhereHas('pharmaceuticalItem', fn($q2) =>
                      $q2->where('drug_name', 'ilike', "%{$search}%")
                  );
            });
        }

        // Filter by active / finished
        if ($request->status === 'active') {
            $query->where('start_date', '<=', today())
                  ->where(fn($q) =>
                      $q->whereNull('finish_date')
                        ->orWhere('finish_date', '>=', today())
                  );
        } elseif ($request->status === 'finished') {
            $query->whereNotNull('finish_date')
                  ->where('finish_date', '<', today());
        }

        $medications = $query
            ->orderBy('start_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('patient_medications.index', compact('medications'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        $drugs = PharmaceuticalItem::orderBy('drug_name')
            ->get(['drug_number', 'drug_name', 'dosage']);

        
        $patients = \App\Models\Patient::orderBy('last_name')->get(['patient_number', 'first_name', 'last_name']);

        return view('patient_medications.create', compact('drugs', 'patients'));
    }

    // ──────────────────────────────────────────────
    // STORE new record
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_number' => 'required|string|exists:patients,patient_number',
            'drug_number'    => 'required|string|exists:pharmaceutical_items,drug_number',
            'units_per_day'  => 'required|integer|min:1',
            'start_date'     => 'required|date',
            'finish_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        // Enforce composite PK uniqueness manually
        $duplicate = PatientMedication::where('patient_number', $validated['patient_number'])
            ->where('drug_number', $validated['drug_number'])
            ->where('start_date',  $validated['start_date'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'drug_number' => 'This patient already has this drug prescribed from that start date.',
                ]);
        }

        PatientMedication::create($validated);

        return redirect()
            ->route('patient_medications.index')
            ->with('success', 'Medication record created successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single record
    // Composite PK passed as query params:
    // ?patient_number=P001&drug_number=D001&start_date=2025-01-01
    // ──────────────────────────────────────────────
    public function show(Request $request)
    {
        $medication = $this->findOrFailByCompositeKey($request);
        $medication->load('pharmaceuticalItem');

        return view('patient_medications.show', compact('medication'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(Request $request)
    {
        $medication = $this->findOrFailByCompositeKey($request);

        $drugs = PharmaceuticalItem::orderBy('drug_name')
            ->get(['drug_number', 'drug_name', 'dosage']);

        $patients = \App\Models\Patient::orderBy('last_name')->get(['patient_number', 'first_name', 'last_name']);

        return view('patient_medications.edit', compact('medication', 'drugs', 'patients'));
    }

    // ──────────────────────────────────────────────
    // UPDATE record
    // Only units_per_day and finish_date are editable.
    // The composite PK fields (patient_number, drug_number, start_date)
    // are immutable — they identify the record.
    // ──────────────────────────────────────────────
    public function update(Request $request)
    {
        $validated = $request->validate([
            'patient_number' => 'required|string|exists:patients,patient_number',
            'drug_number'    => 'required|string|exists:pharmaceutical_items,drug_number',
            'start_date'     => 'required|date',
            'units_per_day'  => 'required|integer|min:1',
            'finish_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $updated = PatientMedication::where('patient_number', $validated['patient_number'])
            ->where('drug_number', $validated['drug_number'])
            ->where('start_date',  $validated['start_date'])
            ->update([
                'units_per_day' => $validated['units_per_day'],
                'finish_date'   => $validated['finish_date'],
            ]);

        if (! $updated) {
            abort(404, 'Medication record not found.');
        }

        return redirect()
            ->route('patient_medications.index')
            ->with('success', 'Medication record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE record
    // ──────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $deleted = PatientMedication::where('patient_number', $request->patient_number)
            ->where('drug_number', $request->drug_number)
            ->where('start_date',  $request->start_date)
            ->delete();

        if (! $deleted) {
            abort(404, 'Medication record not found.');
        }

        return redirect()
            ->route('patient_medications.index')
            ->with('success', 'Medication record deleted successfully.');
    }

    // ──────────────────────────────────────────────
    // PRIVATE HELPER — resolve composite PK from request
    // ──────────────────────────────────────────────
    private function findOrFailByCompositeKey(Request $request): PatientMedication
    {
        return PatientMedication::where('patient_number', $request->patient_number)
            ->where('drug_number', $request->drug_number)
            ->where('start_date',  $request->start_date)
            ->firstOrFail();
    }
}