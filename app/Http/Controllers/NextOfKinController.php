<?php

namespace App\Http\Controllers;

use App\Models\NextOfKin;
use App\Models\Patient;
use Illuminate\Http\Request;

class NextOfKinController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all next-of-kin for a specific patient
    // Route: GET /patients/{patient}/next_of_kins
    // ──────────────────────────────────────────────
    public function index(Patient $patient)
    {
        $nextOfKins = $patient->nextOfKins()->orderBy('full_name')->paginate(10);

        return view('next_of_kins.index', compact('patient', 'nextOfKins'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // Route: GET /patients/{patient}/next_of_kins/create
    // ──────────────────────────────────────────────
    public function create(Patient $patient)
    {
        return view('next_of_kins.create', compact('patient'));
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

        // Attach to the patient resolved from the route
        $patient->nextOfKins()->create($validated);

        return redirect()
            ->route('patients.next_of_kins.index', $patient->patient_number)
            ->with('success', 'Next-of-kin record added successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single next-of-kin record
    // Route: GET /patients/{patient}/next_of_kins/{next_of_kin}
    // ──────────────────────────────────────────────
    public function show(Patient $patient, NextOfKin $nextOfKin)
    {
        $this->authorizeRelationship($patient, $nextOfKin);

        return view('next_of_kins.show', compact('patient', 'nextOfKin'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // Route: GET /patients/{patient}/next_of_kins/{next_of_kin}/edit
    // ──────────────────────────────────────────────
    public function edit(Patient $patient, NextOfKin $nextOfKin)
    {
        $this->authorizeRelationship($patient, $nextOfKin);

        return view('next_of_kins.edit', compact('patient', 'nextOfKin'));
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

        return redirect()
            ->route('patients.next_of_kins.index', $patient->patient_number)
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

        return redirect()
            ->route('patients.next_of_kins.index', $patient->patient_number)
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