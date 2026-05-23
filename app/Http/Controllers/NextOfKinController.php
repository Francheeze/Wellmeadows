<?php

namespace App\Http\Controllers;

use App\Models\NextOfKin;
use App\Models\Patient;
use Illuminate\Http\Request;

class NextOfKinController extends Controller
{
    // ──────────────────────────────────────────────
    // SHOW create form
    // Route: GET /patients/{patient}/next_of_kins/create
    // ──────────────────────────────────────────────
    public function create(Patient $patient)
    {
        // FIX: view path updated to match patients/next_of_kins/ folder
        return view('patients.next_of_kins.create', compact('patient'));
    }

    // ──────────────────────────────────────────────
    // STORE new next-of-kin record
    // Route: POST /patients/{patient}/next_of_kins
    // ──────────────────────────────────────────────
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'next_of_kin_id'   => 'required|string|max:20|unique:next_of_kins,next_of_kin_id',
            'full_name'        => 'required|string|max:100',
            'relationship'     => 'required|string|max:50',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
        ]);

        $patient->nextOfKins()->create($validated);

        // FIX: redirect to patient profile — the next-of-kin table is already there
        // FIX: pass $patient model directly instead of $patient->patient_number
        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Next-of-kin record added successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // Route: GET /patients/{patient}/next_of_kins/{next_of_kin}/edit
    // ──────────────────────────────────────────────
    public function edit(Patient $patient, NextOfKin $nextOfKin)
    {
        $this->authorizeRelationship($patient, $nextOfKin);

        // FIX: view path updated to match patients/next_of_kins/ folder
        return view('patients.next_of_kins.edit', compact('patient', 'nextOfKin'));
    }

    // ──────────────────────────────────────────────
    // UPDATE next-of-kin record
    // next_of_kin_id and patient_number are immutable
    // Route: PUT /patients/{patient}/next_of_kins/{next_of_kin}
    // ──────────────────────────────────────────────
    public function update(Request $request, Patient $patient, NextOfKin $nextOfKin)
    {
        $this->authorizeRelationship($patient, $nextOfKin);

        $validated = $request->validate([
            'full_name'        => 'required|string|max:100',
            'relationship'     => 'required|string|max:50',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
        ]);

        $nextOfKin->update($validated);

        // FIX: redirect to patient profile
        // FIX: pass $patient model directly
        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Next-of-kin record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE next-of-kin record
    // Route: DELETE /patients/{patient}/next_of_kins/{next_of_kin}
    // ──────────────────────────────────────────────
    public function destroy(Patient $patient, NextOfKin $nextOfKin)
    {
        $this->authorizeRelationship($patient, $nextOfKin);

        $nextOfKin->delete();

        // FIX: redirect to patient profile
        // FIX: pass $patient model directly
        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Next-of-kin record deleted successfully.');
    }

    // ──────────────────────────────────────────────
    // PRIVATE HELPER
    // Ensures the next-of-kin record belongs to the
    // patient in the route — prevents URL manipulation
    // e.g. /patients/P002/next_of_kins/NOK001
    // where NOK001 actually belongs to P001
    // ──────────────────────────────────────────────
    private function authorizeRelationship(Patient $patient, NextOfKin $nextOfKin): void
    {
        if ($nextOfKin->patient_number !== $patient->patient_number) {
            abort(404);
        }
    }
}