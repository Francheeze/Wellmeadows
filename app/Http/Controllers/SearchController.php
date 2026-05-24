<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Master list of all departments
        $allDepartments = [
            'Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Emergency', 'Radiology'
        ];

        // Search staff
        $staff = Staff::where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->get(['staff_number', 'first_name', 'last_name', 'department']);

        // Filter the master list of departments based on the query
        $filteredDepartments = collect($allDepartments)
            ->filter(function ($department) use ($query) {
                // Use case-insensitive comparison
                return str_contains(strtolower($department), strtolower($query));
            })
            ->map(function ($department) {
                // Format for the JSON response
                return ['department' => $department];
            })
            ->values(); // Reset keys

        return response()->json([
            'staff' => $staff,
            'departments' => $filteredDepartments,
        ]);
    }
}