<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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

        return view('dashboard', [
            'totalStaff' => $totalStaff,
            'departments' => $departments,
            'debugDepartmentCounts' => $departmentCounts, // Add this for debugging
        ]);
    }
}