<?php

namespace App\Http\Controllers;

use App\Models\OutPatient;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class OutPatientController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all out-patient records
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = OutPatient::with(['patient', 'appointment.examResult']);

        // Search by patient number, name, or appointment number
        if ($request->filled('search')) {   
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('out_patients.patient_number',    'ilike', "%{$search}%")
                  ->orWhereHas('patient', fn($q2) =>
                      $q2->where('first_name',      'ilike', "%{$search}%")
                         ->orWhere('last_name',      'ilike', "%{$search}%")
                  );
                if (is_numeric($search)) {
                    $q->orWhere('out_patients.appointment_number', (int) $search);
                }
            });
        }

        $outPatients = $query
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('out_patients.index', compact('outPatients'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // Only appointments that:
    //   - have an exam result of Out-patient
    //   - do NOT already have an out-patient record
    // ──────────────────────────────────────────────
    public function create()
    {
        $appointments = Appointment::with('patient')
            ->whereHas('examResult', fn($q) => $q->where('result', 'Out-patient'))
            ->whereDoesntHave('outPatient')
            ->orderBy('date_time', 'desc')
            ->get();

        $patients = Patient::orderBy('last_name')
            ->get(['patient_number', 'first_name', 'last_name']);

        return view('out_patients.create', compact('appointments', 'patients'));
    }

    // ──────────────────────────────────────────────
    // STORE new out-patient record
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_number'    => [
                'required',
                'string',
                'exists:appointments,appointment_number',
                'unique:out_patients,appointment_number',
            ],
            'patient_number'        => 'required|string|exists:patients,patient_number',
            'appointment_date_time' => 'required|date',
        ]);

        // Verify the exam result is Out-patient before allowing creation
        $appointment = Appointment::with('examResult')->find($validated['appointment_number']);

        if (!$appointment->examResult || $appointment->examResult->result !== 'Out-patient') {
            return back()
                ->withInput()
                ->withErrors(['appointment_number' => 'The selected appointment does not have an Out-patient exam result.']);
        }

        OutPatient::create($validated);

        return redirect()
            ->route('out_patients.index')
            ->with('success', 'Out-patient record created successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single out-patient record
    // ──────────────────────────────────────────────
    public function show(OutPatient $outPatient)
    {
        $outPatient->load([
            'patient.localDoctor',
            'patient.nextOfKins',
            'appointment.examResult',
        ]);

        return view('out_patients.show', compact('outPatient'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // appointment_number and patient_number are
    // immutable — only appointment_date_time is editable
    // ──────────────────────────────────────────────
    public function edit(OutPatient $outPatient)
    {
        $outPatient->load(['patient', 'appointment']);

        return view('out_patients.edit', compact('outPatient'));
    }

    // ──────────────────────────────────────────────
    // UPDATE out-patient record
    // ──────────────────────────────────────────────
    public function update(Request $request, OutPatient $outPatient)
    {
        $validated = $request->validate([
            'appointment_date_time' => 'required|date',
        ]);

        $outPatient->update($validated);

        return redirect()
            ->route('out_patients.index')
            ->with('success', 'Out-patient record updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE out-patient record
    // ──────────────────────────────────────────────
    public function destroy(OutPatient $outPatient)
    {
        $outPatient->delete();

        return redirect()
            ->route('out_patients.index')
            ->with('success', 'Out-patient record deleted successfully.');
    }
}