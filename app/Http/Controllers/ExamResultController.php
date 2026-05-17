<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\Appointment;
use App\Models\OutPatient;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    // ──────────────────────────────────────────────
    // LIST all exam results
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = ExamResult::with(['appointment.patient']);

        // Search by appointment number or patient name/number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'ilike', "%{$search}%")
                  ->orWhereHas('appointment.patient', fn($q2) =>
                      $q2->where('first_name',     'ilike', "%{$search}%")
                         ->orWhere('last_name',     'ilike', "%{$search}%")
                         ->orWhere('patient_number','ilike', "%{$search}%")
                  );
            });
        }

        // Filter by result type
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        $results         = $query->orderBy('examined_date', 'desc')->paginate(15)->withQueryString();
        $outPatientCount = ExamResult::where('result', 'Out-patient')->count();
        $waitingListCount= ExamResult::where('result', 'WaitingList')->count();

        return view('exam_results.index', compact('results', 'outPatientCount', 'waitingListCount'));
    }

    // ──────────────────────────────────────────────
    // SHOW create form
    // Only appointments without an existing exam result
    // are offered as options
    // ──────────────────────────────────────────────
    public function create()
    {
        $appointments = Appointment::with('patient')
            ->whereDoesntHave('examResult')
            ->where('date_time', '<=', now()) // can only record result for past/current appointments
            ->orderBy('date_time', 'desc')
            ->get();

        return view('exam_results.create', compact('appointments'));
    }

    // ──────────────────────────────────────────────
    // STORE new exam result
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_number' => [
                'required',
                'string',
                'exists:appointments,appointment_number',
                'unique:exam_results,appointment_number',
            ],
            'result'        => 'required|in:Out-patient,WaitingList',
            'examined_date' => 'required|date',
        ]);

        ExamResult::create($validated);

        // Auto-create out-patient record if result is Out-patient
        if ($validated['result'] === 'Out-patient') {
            $appointment = Appointment::find($validated['appointment_number']);

            if (!$appointment->outPatient()->exists()) {
                OutPatient::create([
                    'appointment_number'    => $appointment->appointment_number,
                    'patient_number'        => $appointment->patient_number,
                    'appointment_date_time' => $appointment->date_time,
                ]);
            }

            return redirect()
                ->route('exam_results.index')
                ->with('success', 'Exam result recorded. Patient classified as Out-patient.');
        }

        // WaitingList → prompt staff to complete in-patient admission
        return redirect()
            ->route('in_patients.create', ['appointment_number' => $validated['appointment_number']])
            ->with('success', 'Exam result recorded. Please complete the in-patient admission details.');
    }

    // ──────────────────────────────────────────────
    // SHOW single exam result
    // ──────────────────────────────────────────────
    public function show(ExamResult $examResult)
    {
        $examResult->load([
            'appointment.patient.localDoctor',
            'appointment.inPatient',
            'appointment.outPatient',
        ]);

        return view('exam_results.show', compact('examResult'));
    }

    // ──────────────────────────────────────────────
    // SHOW edit form
    // ──────────────────────────────────────────────
    public function edit(ExamResult $examResult)
    {
        $examResult->load('appointment.patient');

        return view('exam_results.edit', compact('examResult'));
    }

    // ──────────────────────────────────────────────
    // UPDATE exam result
    // appointment_number is immutable — it is the PK.
    // Only result and examined_date are editable.
    // Guard: if a downstream in-patient or out-patient record
    // already exists, block result type changes to prevent
    // data inconsistency.
    // ──────────────────────────────────────────────
    public function update(Request $request, ExamResult $examResult)
    {
        $validated = $request->validate([
            'result'        => 'required|in:Out-patient,WaitingList',
            'examined_date' => 'required|date',
        ]);

        $appointment = $examResult->appointment;

        // Block result type change if downstream records exist
        if ($validated['result'] !== $examResult->result) {
            if ($appointment->inPatient()->exists()) {
                return back()->with('error', 'Cannot change result — an in-patient admission record already exists for this appointment.');
            }
            if ($appointment->outPatient()->exists()) {
                return back()->with('error', 'Cannot change result — an out-patient record already exists for this appointment.');
            }

            // If result changed to Out-patient, auto-create out-patient record
            if ($validated['result'] === 'Out-patient') {
                OutPatient::create([
                    'appointment_number'    => $appointment->appointment_number,
                    'patient_number'        => $appointment->patient_number,
                    'appointment_date_time' => $appointment->date_time,
                ]);
            }
        }

        $examResult->update($validated);

        return redirect()
            ->route('exam_results.index')
            ->with('success', 'Exam result updated successfully.');
    }

    // ──────────────────────────────────────────────
    // DELETE exam result
    // Blocked if a downstream in-patient or out-patient
    // record already exists
    // ──────────────────────────────────────────────
    public function destroy(ExamResult $examResult)
    {
        $appointment = $examResult->appointment;

        if ($appointment->inPatient()->exists()) {
            return back()->with('error', 'Cannot delete — an in-patient admission record is linked to this result.');
        }

        if ($appointment->outPatient()->exists()) {
            return back()->with('error', 'Cannot delete — an out-patient record is linked to this result.');
        }

        $examResult->delete();

        return redirect()
            ->route('exam_results.index')
            ->with('success', 'Exam result deleted successfully.');
    }
}