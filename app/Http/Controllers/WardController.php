<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Bed;
use App\Models\Staff;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards         = Ward::all();
        $beds          = Bed::all();
        $totalWards    = $wards->count();
        $totalBeds     = $wards->sum('total_beds');
        $occupiedBeds  = $beds->where('status', 'occupied')->count();
        $availableBeds = $beds->where('status', 'available')->count();

        return view('wards.index', compact(
            'wards', 'totalWards', 'totalBeds', 'occupiedBeds', 'availableBeds'
        ));
    }

    public function create()
    {
        $staffList = Staff::where('position', 'Charge Nurse')->get();
        return view('wards.create', compact('staffList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_number'         => 'required|unique:wards,ward_number',
            'ward_name'           => 'required',
            'location'            => 'nullable',
            'total_beds'          => 'nullable|integer',
            'telephone_extention' => 'nullable',
            'charge_nurse_number' => 'nullable|exists:staff,staff_number',
        ]);

        Ward::create($request->all());

        return redirect()->route('wards.index')->with('success', 'Ward added successfully!');
    }

    public function edit($ward_number)
    {
        $ward      = Ward::findOrFail($ward_number);
        $staffList = Staff::where('position', 'Charge Nurse')->get();
        return view('wards.edit', compact('ward', 'staffList'));
    }

    public function update(Request $request, $ward_number)
    {
        $ward = Ward::findOrFail($ward_number);

        $request->validate([
            'ward_name'           => 'required',
            'location'            => 'nullable',
            'total_beds'          => 'nullable|integer',
            'telephone_extention' => 'nullable',
            'charge_nurse_number' => 'nullable|exists:staff,staff_number',
        ]);

        $ward->update($request->only([
            'ward_name', 'location', 'total_beds',
            'telephone_extention', 'charge_nurse_number'
        ]));

        return redirect()->route('wards.index')->with('success', 'Ward updated successfully!');
    }

    public function destroy($ward_number)
    {
        $ward = Ward::findOrFail($ward_number);
        $ward->delete();

        return redirect()->route('wards.index')->with('success', 'Ward deleted successfully!');
    }
}