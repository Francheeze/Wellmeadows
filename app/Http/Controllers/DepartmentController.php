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
        // Define all departments with their details
        $departments = [
            'Cardiology' => ['emoji' => '❤️', 'description' => 'Comprehensive heart care including diagnosis, treatment, and prevention of cardiovascular diseases.', 'color' => 'bg-blue-500'],
            'Neurology' => ['emoji' => '🧠', 'description' => 'Specialized care for disorders of the nervous system including brain, spinal cord, and nerves.', 'color' => 'bg-green-500'],
            'Pediatrics' => ['emoji' => '👶', 'description' => 'Medical care for infants, children, and adolescents from birth to young adulthood.', 'color' => 'bg-purple-500'],
            'Orthopedics' => ['emoji' => '🦴', 'description' => 'Treatment of musculoskeletal system including bones, joints, ligaments, tendons, and muscles.', 'color' => 'bg-yellow-500'],
            'Emergency' => ['emoji' => '🚑', 'description' => '24/7 emergency care for acute illnesses and injuries requiring immediate medical attention.', 'color' => 'bg-red-500'],
            'Radiology' => ['emoji' => '📊', 'description' => 'Medical imaging techniques including X-rays, CT scans, MRI, and ultrasound for diagnosis.', 'color' => 'bg-indigo-500']
        ];

        // Get the count of staff for each department, case-insensitively
        $departmentCounts = Staff::query()
            ->select(DB::raw('LOWER(department) as department_lower'), DB::raw('count(*) as staff_count'))
            ->whereNotNull('department')
            ->groupBy('department_lower')
            ->pluck('staff_count', 'department_lower');

        // Pass both the department details and the counts to the view
        return view('department', compact('departments', 'departmentCounts'));
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