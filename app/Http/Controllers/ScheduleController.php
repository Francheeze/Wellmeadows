<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('staff')->paginate(10);
        $staff = Staff::all();
        return view('schedules.index', compact('schedules', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        $departments = ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Emergency', 'Radiology'];
        return view('schedules.create', compact('staff', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,staff_number',
            'department' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Schedule added successfully.');
    }
}