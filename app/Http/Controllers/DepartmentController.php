<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->has('search') && $request->input('search')) {
            $query->where('name', 'ILIKE', '%' . $request->input('search') . '%');
        }

        $departments = $query->paginate(10);
        $all_departments = Department::all();
        $totalStaff = Staff::count();

        // Group by department name string column
        $countByDepartment = Staff::query()
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department');

        // Map department name -> count so the view can look up by name
        $departmentCounts = Department::all()->mapWithKeys(function ($dept) use ($countByDepartment) {
            return [$dept->name => $countByDepartment->get($dept->name, 0)];
        });

        $departmentIcons = [
            'Cardiology' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />',
            'Neurology' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v.01M12 12v.01M12 16v.01M12 4a4 4 0 00-4 4v.717l-1.717 1.717a1 1 0 000 1.414l1.717 1.717V16a4 4 0 004 4h.01M16 4a4 4 0 014 4v.717l1.717 1.717a1 1 0 010 1.414l-1.717 1.717V16a4 4 0 01-4 4h-.01" />',
            'Pediatrics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
            'Orthopedics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M7 4h2M7 8h2M7 12h2M7 16h2M17 4v16M17 4h-2M17 8h-2M17 12h-2M17 16h-2" />',
            'Emergency' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
            'Radiology' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
        ];

        return view('departments.index', compact('departments', 'all_departments', 'totalStaff', 'departmentCounts', 'departmentIcons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) {
                    if (Department::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'address' => 'required|string|max:255',
        ]);

        Department::create($validatedData);

        return redirect()->route('department.index')
                         ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        // Filter staff by department name string column
        $staff = Staff::where('department', $department->name)->paginate(10);
        return view('departments.show', compact('department', 'staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($department) {
                    if (Department::where('id', '!=', $department->id)->whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'address' => 'required|string|max:255',
        ]);

        $department->update($request->all());

        return redirect()->route('department.index')
                         ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('department.index')
                         ->with('success', 'Department deleted successfully.');
    }
}