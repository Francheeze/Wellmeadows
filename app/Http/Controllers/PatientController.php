<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\LocalDoctor;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all patients
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Patient::with('localDoctor');

        // Search by name, patient number, or telephone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_number',    'ilike', "%{$search}%")
                  ->orWhere('first_name',      'ilike', "%{$search}%")
                  ->orWhere('last_name',       'ilike', "%{$search}%")
                  ->orWhere('telephone_number','ilike', "%{$search}%");
            });
        }

        // Filter by sex
        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        // Filter by marital status
        if ($request->filled('marital_status')) {
            $query->where('marital_status', $request->marital_status);
        }

        $patients   = $query->orderBy('last_name')->paginate(15)->withQueryString();
        $totalCount = Patient::count();

        return view('patients.index', compact('patients', 'totalCount'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        $doctors = LocalDoctor::orderBy('full_name')
            ->get(['clinic_number', 'full_name', 'telephone_number']);

        return view('patients.create', compact('doctors'));
    }

    // ──────────────────────────────────────────────
    // STORE new patient
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_number'   => 'required|string|max:20|unique:patients,patient_number',
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth'    => 'required|date|before:today',
            'sex'              => 'required|in:Male,Female,Other',
            'marital_status'   => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'date_registered'  => 'required|date',
            'referred_by'      => 'nullable|string|exists:local_doctors,clinic_number',
        ]);

        Patient::create($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient registered successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single patient — full profile
    // ──────────────────────────────────────────────
    public function show(Patient $patient)
    {
        $patient->load([
            'localDoctor',
            'nextOfKins',
            'appointments.examResult',
            'appointments.inPatient',
            'appointments.outPatient',
            'inPatientRecords',
            'outPatientRecords',
        ]);

        return view('patients.show', compact('patient'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(Patient $patient)
    {
        $doctors = LocalDoctor::orderBy('full_name')
            ->get(['clinic_number', 'full_name', 'telephone_number']);

        return view('patients.edit', compact('patient', 'doctors'));
    }

    // ──────────────────────────────────────────────
    // UPDATE patient
    // patient_number is immutable after registration
    // ──────────────────────────────────────────────
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth'    => 'required|date|before:today',
            'sex'              => 'required|in:Male,Female,Other',
            'marital_status'   => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'date_registered'  => 'required|date',
            'referred_by'      => 'nullable|string|exists:local_doctors,clinic_number',
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE patient
    // Blocked if any appointments, in-patient or
    // out-patient records are linked
    // ──────────────────────────────────────────────
    public function destroy(Patient $patient)
    {
        if ($patient->appointments()->exists()) {
            return back()->with('error', 'Cannot delete — this patient has appointment records.');
        }

        if ($patient->inPatientRecords()->exists()) {
            return back()->with('error', 'Cannot delete — this patient has in-patient admission records.');
        }

        if ($patient->outPatientRecords()->exists()) {
            return back()->with('error', 'Cannot delete — this patient has out-patient records.');
        }

        // next_of_kins cascade-delete automatically (set in migration)
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient record deleted successfully.');
    }
}