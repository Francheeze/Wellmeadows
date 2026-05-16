<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('staff')->get();
        $staff = Staff::all();
        return view('schedules', compact('schedules', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,staffNumber',
            'department' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules')->with('success', 'Schedule added successfully.');
    }
}