<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Bed;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards         = Ward::all();
        $beds          = Bed::all();
        $totalWards    = $wards->count();
        $totalBeds     = $wards->sum('totalbeds');
        $occupiedBeds  = $beds->where('status', 'occupied')->count();
        $availableBeds = $beds->where('status', 'available')->count();

        return view('wards.index', compact(
            'wards', 'totalWards', 'totalBeds', 'occupiedBeds', 'availableBeds'
        ));
    }

    public function create()
    {
        return view('wards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'wardnumber'         => 'required|unique:wards,wardnumber',
            'wardname'           => 'required',
            'location'           => 'nullable',
            'totalbeds'          => 'nullable|integer',
            'telephoneextention' => 'nullable',
            'chargenursenumber'  => 'nullable',
        ]);

        Ward::create($request->all());

        return redirect()->route('wards.index')->with('success', 'Ward added successfully!');
    }

    public function edit($id)
    {
        $ward = Ward::findOrFail($id);
        return view('wards.edit', compact('ward'));
    }

    public function update(Request $request, $id)
    {
        $ward = Ward::findOrFail($id);

        $request->validate([
            'wardname'           => 'required',
            'location'           => 'nullable',
            'totalbeds'          => 'nullable|integer',
            'telephoneextention' => 'nullable',
            'chargenursenumber'  => 'nullable',
        ]);

        $ward->update($request->only([
            'wardname', 'location', 'totalbeds',
            'telephoneextention', 'chargenursenumber'
        ]));

        return redirect()->route('wards.index')->with('success', 'Ward updated successfully!');
    }

    public function destroy($id)
    {
        $ward = Ward::findOrFail($id);
        $ward->delete();

        return redirect()->route('wards.index')->with('success', 'Ward deleted successfully!');
    }
}