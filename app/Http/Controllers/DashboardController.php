<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Staff;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\StaffRota;
use App\Models\Department;
use App\Models\SupplyItem;
use App\Models\Supplier;
use App\Models\InPatient;
use App\Models\OutPatient;
use App\Models\Appointment;
use App\Models\Requisition;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            // Patients
            'totalPatients'      => Patient::count(),
            'malePatients'       => Patient::where('sex', 'M')->count(),
            'femalePatients'     => Patient::where('sex', 'F')->count(),
            'inPatients'         => InPatient::count(),
            'outPatients'        => OutPatient::count(),

            // Ward and Bed
            'totalWards'         => Ward::count(),
            'totalBeds'          => Bed::count(),
            'availableBeds'      => Bed::where('status', 'available')->count(),
            'occupiedBeds'       => Bed::where('status', 'occupied')->count(),
            'staffOnRota'        => StaffRota::count(),

            // Staff and Department
            'totalStaff'         => Staff::count(),
            'totalDepartments'   => Department::count(),
            'chargeNurses'       => Staff::where('position', 'Charge Nurse')->count(),
            'earlyShift'         => StaffRota::where('shift', 'Early')->count(),
            'nightShift'         => StaffRota::where('shift', 'Night')->count(),

            // Appointment and Requisition
            'totalSupplies'      => SupplyItem::count(),
            'totalSuppliers'     => Supplier::count(),
            'lowStock'           => SupplyItem::whereRaw('quantity_in_stock <= reorder_level')->count(),
            'outOfStock'         => SupplyItem::where('quantity_in_stock', 0)->count(),
            'totalAppointments'  => Appointment::count(),
            'upcomingAppointments' => Appointment::where('date_time', '>=', Carbon::now())->count(),
            'totalRequisitions'  => Requisition::count(),
        ];

        return view('dashboard', $data);
    }
}