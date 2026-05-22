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
        $wardsCovered  = $beds->pluck('wardnumber')->unique()->count();

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
            'bednumber'  => 'required|unique:beds,bednumber',
            'wardnumber' => 'required|exists:wards,wardnumber',
            'status'     => 'required|in:available,occupied',
        ]);

        Bed::create($request->all());

        return redirect()->route('beds.index')->with('success', 'Bed added successfully!');
    }

    public function edit($id)
    {
        $bed   = Bed::findOrFail($id);
        $wards = Ward::all();
        return view('beds.edit', compact('bed', 'wards'));
    }

    public function update(Request $request, $id)
    {
        $bed = Bed::findOrFail($id);

        $request->validate([
            'wardnumber' => 'required|exists:wards,wardnumber',
            'status'     => 'required|in:available,occupied',
        ]);

        $bed->update($request->only(['wardnumber', 'status']));

        return redirect()->route('beds.index')->with('success', 'Bed updated successfully!');
    }

    public function destroy($id)
    {
        $bed = Bed::findOrFail($id);
        $bed->delete();

        return redirect()->route('beds.index')->with('success', 'Bed deleted successfully!');
    }
}