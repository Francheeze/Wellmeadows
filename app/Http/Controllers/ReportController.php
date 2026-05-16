<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Incident;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $departmentCounts = Staff::whereNotNull('department')
            ->select('department', \DB::raw('count(*) as staff_count'))
            ->groupBy('department')
            ->pluck('staff_count', 'department');

        return view('dashboard', compact('departmentCounts'));
    }
}