<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $totalStaff = Staff::count();

        $departmentCounts = Staff::query()
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department');

        $departments = [
            'Cardiology' => 0,
            'Neurology' => 0,
            'Pediatrics' => 0,
            'Orthopedics' => 0,
            'Emergency' => 0,
            'Radiology' => 0,
        ];

        foreach ($departmentCounts as $department => $count) {
            if (array_key_exists($department, $departments)) {
                $departments[$department] = $count;
            }
        }

        return view('departments.index', [
            'totalStaff' => $totalStaff,
            'departments' => $departments,
        ]);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $name)
    {
        $departmentName = $name;

        // Fetch staff members for the specified department, case-insensitive
        $staff = Staff::whereRaw('LOWER(department) = ?', [strtolower($departmentName)])->get();

        return view('departments.show', compact('staff', 'departmentName'));
    }
}