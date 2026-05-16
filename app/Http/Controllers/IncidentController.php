<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Staff;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidents = Incident::with('staff')->latest()->get();
        return view('reports', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staff = Staff::all();
        return view('reports.create', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,staffNumber',
            'incident_date' => 'required|date',
            'incident_type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Incident::create($validated);

        return redirect()->route('reports')->with('success', 'Incident report created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        $incident->delete();
        return redirect()->route('reports')->with('success', 'Incident report deleted successfully.');
    }
}