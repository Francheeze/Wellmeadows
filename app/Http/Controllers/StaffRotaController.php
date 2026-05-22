<?php

namespace App\Http\Controllers;

use App\Models\StaffRota;
use App\Models\Ward;
use Illuminate\Http\Request;

class StaffRotaController extends Controller
{
    public function index()
    {
        $staffRotas = StaffRota::with('ward')->orderBy('wardnumber')->get();
        $wards      = Ward::all();

        return view('staff_rota.index', compact('staffRotas', 'wards'));
    }

    public function create()
    {
        $wards = Ward::all();
        return view('staff_rota.create', compact('wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wardnumber'    => 'required|exists:wards,wardnumber',
            'staffnumber'   => 'required',
            'shift'         => 'required|in:Early,Late,Night',
            'weekstartdate' => 'required|date',
        ]);

        StaffRota::create($request->all());

        return redirect()->route('staff-rota.index')->with('success', 'Staff added to rota!');
    }

    public function edit($id)
    {
        $staffRota = StaffRota::findOrFail($id);
        $wards     = Ward::all();
        return view('staff_rota.edit', compact('staffRota', 'wards'));
    }

    public function update(Request $request, $id)
    {
        $staffRota = StaffRota::findOrFail($id);

        $request->validate([
            'wardnumber'    => 'required|exists:wards,wardnumber',
            'staffnumber'   => 'required',
            'shift'         => 'required|in:Early,Late,Night',
            'weekstartdate' => 'required|date',
        ]);

        $staffRota->update($request->only([
            'wardnumber', 'staffnumber', 'shift', 'weekstartdate'
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