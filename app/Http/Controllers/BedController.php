<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        $beds          = Bed::with('ward')->get();
        $wards         = Ward::all();
        $totalBeds     = $beds->count();
        $availableBeds = $beds->where('status', 'available')->count();
        $occupiedBeds  = $beds->where('status', 'occupied')->count();
        $wardsCovered  = $beds->pluck('ward_number')->unique()->count();

        return view('beds.index', compact(
            'beds', 'wards', 'totalBeds', 'availableBeds', 'occupiedBeds', 'wardsCovered'
        ));
    }

    public function create()
    {
        $wards = Ward::all();
        return view('beds.create', compact('wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bed_number'  => 'required|unique:beds,bed_number',
            'ward_number' => 'required|exists:wards,ward_number',
            'status'      => 'required|in:available,occupied',
        ]);

        Bed::create($request->all());

        return redirect()->route('beds.index')->with('success', 'Bed added successfully!');
    }

    public function edit($bed_number)
    {
        $bed   = Bed::findOrFail($bed_number);
        $wards = Ward::all();
        return view('beds.edit', compact('bed', 'wards'));
    }

    public function update(Request $request, $bed_number)
    {
        $bed = Bed::findOrFail($bed_number);

        $request->validate([
            'ward_number' => 'required|exists:wards,ward_number',
            'status'      => 'required|in:available,occupied',
        ]);

        $bed->update($request->only(['ward_number', 'status']));

        return redirect()->route('beds.index')->with('success', 'Bed updated successfully!');
    }

    public function destroy($bed_number)
    {
        $bed = Bed::findOrFail($bed_number);
        $bed->delete();

        return redirect()->route('beds.index')->with('success', 'Bed deleted successfully!');
    }
}