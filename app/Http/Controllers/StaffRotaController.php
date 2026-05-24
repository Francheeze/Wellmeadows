<?php

namespace App\Http\Controllers;

use App\Models\StaffRota;
use App\Models\Ward;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffRotaController extends Controller
{
    public function index()
    {
        $staffRotas = StaffRota::with('ward', 'staff')->orderBy('ward_number')->get();
        $wards      = Ward::all();

        return view('staff_rota.index', compact('staffRotas', 'wards'));
    }

    public function create()
    {
        $wards     = Ward::all();
        $staffList = Staff::all();
        return view('staff_rota.create', compact('wards', 'staffList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_number'    => 'required|exists:wards,ward_number',
            'staff_number'   => 'required|exists:staff,staffNumber',
            'shift'          => 'required|in:Early,Late,Night',
            'week_start_date'=> 'required|date',
        ]);

        StaffRota::create($request->all());

        return redirect()->route('staff-rota.index')->with('success', 'Staff added to rota!');
    }

    public function edit($id)
    {
        $staffRota = StaffRota::findOrFail($id);
        $wards     = Ward::all();
        $staffList = Staff::all();
        return view('staff_rota.edit', compact('staffRota', 'wards', 'staffList'));
    }

    public function update(Request $request, $id)
    {
        $staffRota = StaffRota::findOrFail($id);

        $request->validate([
            'ward_number'    => 'required|exists:wards,ward_number',
            'staff_number'   => 'required|exists:staff,staffNumber',
            'shift'          => 'required|in:Early,Late,Night',
            'week_start_date'=> 'required|date',
        ]);

        $staffRota->update($request->only([
            'ward_number', 'staff_number', 'shift', 'week_start_date'
        ]));

        return redirect()->route('staff-rota.index')->with('success', 'Staff rota updated!');
    }

    public function destroy($id)
    {
        $staffRota = StaffRota::findOrFail($id);
        $staffRota->delete();

        return redirect()->route('staff-rota.index')->with('success', 'Staff removed from rota!');
    }
}