<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\ExamResult;
use App\Models\Staff;           // ← added
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all appointments
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'examResult']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {

                // String columns — safe to use ilike
                $q->where('examination_room', 'ilike', "%{$search}%")
                  ->orWhereHas('patient', fn($q2) =>
                      $q2->where('first_name',      'ilike', "%{$search}%")
                         ->orWhere('last_name',      'ilike', "%{$search}%")
                         ->orWhere('patient_number', 'ilike', "%{$search}%")
                  );

                // Integer columns — only match when the search term is numeric
                if (is_numeric($search)) {
                    $q->orWhere('appointment_number', (int) $search)
                      ->orWhere('staff_number',       (int) $search);
                }
            });
        }

        // Filter by status
        if ($request->status === 'upcoming') {
            $query->where('date_time', '>', now());
        } elseif ($request->status === 'past') {
            $query->where('date_time', '<=', now());
        } elseif ($request->status === 'examined') {
            $query->whereHas('examResult');
        } elseif ($request->status === 'pending') {
            $query->whereDoesntHave('examResult');
        }

        $appointments  = $query->orderBy('date_time', 'desc')->paginate(15)->withQueryString();
        $upcomingCount = Appointment::where('date_time', '>', now())->count();
        $pendingCount  = Appointment::whereDoesntHave('examResult')->count();

        return view('appointments.index', compact('appointments', 'upcomingCount', 'pendingCount'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // ──────────────────────────────────────────────
    public function create()
    {
        $patients = Patient::orderBy('last_name')
            ->get(['patient_number', 'first_name', 'last_name']);

        $staff = Staff::orderBy('last_name')
            ->get(['staff_number', 'first_name', 'last_name']);

        return view('appointments.create', compact('patients', 'staff'));
    }

    // ──────────────────────────────────────────────
    // STORE new appointment
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_number' => 'required|integer|min:1|unique:appointments,appointment_number',
            'patient_number'     => 'required|string|exists:patients,patient_number',
            // staff_number is the PK column name on the staff table
            'staff_number'       => 'required|string|exists:staff,staff_number',
            'date_time'          => 'required|date|after:now',
            'examination_room'   => 'required|string|max:50',
        ]);

        Appointment::create($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    // ──────────────────────────────────────────────
    // SHOW single appointment
    // ──────────────────────────────────────────────
    public function show(Appointment $appointment)
    {
        $appointment->load([
            'patient.localDoctor',
            'patient.nextOfKins',
            'examResult',
            'inPatient',
            'outPatient',
        ]);

        return view('appointments.show', compact('appointment'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('last_name')
            ->get(['patient_number', 'first_name', 'last_name']);

        $staff = Staff::orderBy('lastName')
            ->get(['staffNumber', 'firstName', 'lastName']);

        return view('appointments.edit', compact('appointment', 'patients', 'staff'));
    }

    // ──────────────────────────────────────────────
    // UPDATE appointment
    // appointment_number is immutable after creation
    // ──────────────────────────────────────────────
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_number'   => 'required|string|exists:patients,patient_number',
            'staff_number'     => 'required|string|exists:staff,staff_number',
            'date_time'        => 'required|date',
            'examination_room' => 'required|string|max:50',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE appointment
    // ──────────────────────────────────────────────
    public function destroy(Appointment $appointment)
    {
        if ($appointment->examResult()->exists()) {
            return back()->with('error', 'Cannot delete — this appointment already has an exam result recorded.');
        }

        if ($appointment->inPatient()->exists() || $appointment->outPatient()->exists()) {
            return back()->with('error', 'Cannot delete — this appointment is linked to a patient admission record.');
        }

        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    // ──────────────────────────────────────────────
    // RECORD EXAM RESULT
    // ──────────────────────────────────────────────
    public function recordResult(Request $request, Appointment $appointment)
    {
        if ($appointment->examResult()->exists()) {
            return back()->with('error', 'An exam result has already been recorded for this appointment.');
        }

        $validated = $request->validate([
            'result'        => 'required|in:Out-patient,WaitingList',
            'examined_date' => 'required|date',
        ]);

        ExamResult::create([
            'appointment_number' => $appointment->appointment_number,
            'result'             => $validated['result'],
            'examined_date'      => $validated['examined_date'],
        ]);

        if ($validated['result'] === 'Out-patient') {
            $appointment->outPatient()->create([
                'patient_number'        => $appointment->patient_number,
                'appointment_date_time' => $appointment->date_time,
            ]);

            return redirect()
                ->route('appointments.show', $appointment->appointment_number)
                ->with('success', 'Exam result recorded. Patient classified as Out-patient.');
        }

        return redirect()
            ->route('in_patients.create', ['appointment_number' => $appointment->appointment_number])
            ->with('success', 'Exam result recorded. Please complete the in-patient admission details.');
    }
}