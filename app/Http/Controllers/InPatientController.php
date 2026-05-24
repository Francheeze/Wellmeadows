<?php

namespace App\Http\Controllers;

use App\Models\InPatient;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class InPatientController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all in-patient records
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = InPatient::with(['patient', 'appointment']);

        // Search by patient number, name, ward, or bed
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('in_patients.patient_number', 'ilike', "%{$search}%")
                  ->orWhereHas('patient', fn($q2) =>
                      $q2->where('first_name', 'ilike', "%{$search}%")
                         ->orWhere('last_name',  'ilike', "%{$search}%")
                  );
                if (is_numeric($search)) {
                    $q->orWhere('ward_number', (int) $search)
                      ->orWhere('bed_number',  (int) $search);
                }
            });
        }

        // Filter by admission status
        if ($request->status === 'admitted') {
            $query->whereNull('actual_leave');
        } elseif ($request->status === 'discharged') {
            $query->whereNotNull('actual_leave');
        }

        $inPatients       = $query->orderBy('date_placed', 'desc')->paginate(15)->withQueryString();
        $currentlyAdmitted = InPatient::whereNull('actual_leave')->count();

        return view('in_patients.index', compact('inPatients', 'currentlyAdmitted'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        // Only appointments that don't yet have an in-patient record
        // and whose exam result is WaitingList (admitted)
        $appointments = Appointment::with('patient')
            ->whereHas('examResult', fn($q) => $q->where('result', 'WaitingList'))
            ->whereDoesntHave('inPatient')
            ->orderBy('date_time', 'desc')
            ->get();

        $patients = Patient::orderBy('last_name')->get(['patient_number', 'first_name', 'last_name']);

        return view('in_patients.create', compact('appointments', 'patients'));
    }

    // ──────────────────────────────────────────────
    // STORE new record
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_number' => 'required|string|max:20|unique:in_patients,appointment_number|exists:appointments,appointment_number',
            'patient_number'     => 'required|string|exists:patients,patient_number',
            'ward_number'        => 'required|integer|exists:wards,ward_number',
            'bed_number'         => 'required|integer|exists:beds,bed_number',
            'expected_stay'      => 'required|integer|min:1',
            'date_placed'        => 'required|date',
            'date_leave'         => 'nullable|date|after_or_equal:date_placed',
            'actual_leave'       => 'nullable|date|after_or_equal:date_placed',
        ]);

        InPatient::create($validated);

        return redirect()
            ->route('in_patients.index')
            ->with('success', 'In-patient record created successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single record
    // Route model binding works here — single string PK
    // ──────────────────────────────────────────────
    public function show(InPatient $inPatient)
    {
        $inPatient->load(['patient.localDoctor', 'appointment.examResult']);

        return view('in_patients.show', compact('inPatient'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(InPatient $inPatient)
    {
        $inPatient->load(['patient', 'appointment']);

        return view('in_patients.edit', compact('inPatient'));
    }

    // ──────────────────────────────────────────────
    // UPDATE record
    // appointment_number, patient_number are immutable (they are PKs/FKs)
    // Only ward, bed, stay details and leave dates are editable
    // ──────────────────────────────────────────────
    public function update(Request $request, InPatient $inPatient)
    {
        $validated = $request->validate([
            'ward_number'   => 'required|integer|exists:wards,ward_number',
            'bed_number'    => 'required|integer|exists:beds,bed_number',
            'expected_stay' => 'required|integer|min:1',
            'date_placed'   => 'required|date',
            'date_leave'    => 'nullable|date|after_or_equal:date_placed',
            'actual_leave'  => 'nullable|date|after_or_equal:date_placed',
        ]);

        $inPatient->update($validated);

        return redirect()
            ->route('in_patients.index')
            ->with('success', 'In-patient record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE record
    // ──────────────────────────────────────────────
    public function destroy(InPatient $inPatient)
    {
        $inPatient->delete();

        return redirect()
            ->route('in_patients.index')
            ->with('success', 'In-patient record deleted successfully.');
    }

    // ──────────────────────────────────────────────
    // DISCHARGE — convenience action to set actual_leave to today
    // Called via PATCH from the show or index page
    // ──────────────────────────────────────────────
    public function discharge(InPatient $inPatient)
    {
        if ($inPatient->actual_leave) {
            return back()->with('error', 'Patient has already been discharged.');
        }

        $inPatient->update(['actual_leave' => today()]);

        return redirect()
            ->route('in_patients.show', $inPatient->appointment_number)
            ->with('success', 'Patient discharged successfully.');
    }
}