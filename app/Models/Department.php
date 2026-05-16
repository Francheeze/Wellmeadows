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
        return view('incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staff = Staff::orderBy('firstName')->orderBy('lastName')->get();
        return view('incidents.create', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,staffNumber',
            'incident_date' => 'required|date',
            'incident_level' => 'required|string',
            'description' => 'required|string',
        ]);

        Incident::create($validated);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident reported successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Incident report deleted successfully.');
    }
}