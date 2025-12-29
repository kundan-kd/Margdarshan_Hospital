<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\LabInvestigation;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\User;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function index(){
          $doctors = User::where('usertype_id',2)->get(['id','name']);
      $consultantCount = [];
      foreach($doctors as $doctor){
         $count = PatientLog::where('doctor_id',$doctor->id)->count();
         $consultantCount[] = [
            'doctor_name' => $doctor->name,
            'patient_count' => $count
         ];
      }
      $appointments = PatientLog::where('type','OPD')->count();
      $appointment_visited = PatientLog::where('type','OPD')->where('status','Visited')->orWhere('status','Moved to IPD')->count();
      $appoinrmentsCount = [                          
         'total_appointment' => $appointments,
         'walk_in' => $appointment_visited
      ];
      $totalBIlls = PaymentBill::sum('amount');
      $billReceived = PaymentReceived::sum('amount') + PaymentReceived::sum('discount_amount');
      $totalRevenue = [
         'total_amount' => $totalBIlls,
         'received_amount' => $billReceived
      ];
      $newPatients = PatientLog::select('patient_id')
      ->where('current_status', 'Admitted')
      ->orWhere('status','Moved to IPD')
      ->groupBy('patient_id')
      ->havingRaw('COUNT(*) = 1')
      ->count();
      $repeatPatient = PatientLog::select('patient_id')
         ->where('current_status', 'Admitted')
         ->orWhere('status','Moved to IPD')
         ->groupBy('patient_id')
         ->havingRaw('COUNT(*) > 1')
         ->count();
      $patientCounts = [
         'repeat_patient' => $repeatPatient,
         'new_patient' => $newPatients
      ];

      $emergencyAdded = PatientLog::where('type','EMERGENCY')->where('current_status','Admitted')->count();
      $emergencyAvailable = Patient::where('type','EMERGENCY')->count();
      $emergencyDischarge = Patient::where('type','EMERGENCY')->where('current_status','Discharged')->count();
      $emergencyMoved = $emergencyAdded - $emergencyAvailable;
      $emergencyPatients = [
         'admission' => $emergencyAdded,
         'discharge' => $emergencyDischarge,
         'moved' => $emergencyMoved
      ];
      $icuBeds = Bed::where('bed_group_id',4)->count();
      $icuBedOccupied = Bed::where('bed_group_id',4)->where('current_status','occupied')->count();
      $icuBeds = [
         'total' => $icuBeds,
         'occupied' => $icuBedOccupied
      ];
      $today = Carbon::today();
      $daily_lab = LabInvestigation::whereDate('created_at', $today)->count();
      $weekly_lab = LabInvestigation::whereBetween('created_at', [$today->copy()->subDays(7),$today->endOfDay()])->count();
      $monthly_lab = LabInvestigation::whereBetween('created_at', [$today->copy()->subDays(30),$today->endOfDay()])->count();
      $lab_count = [
         'today_lab' => $daily_lab,
         'weekly_lab' => $weekly_lab,
         'monthly_lab' => $monthly_lab
      ];
      $ipdBeds = Bed::where('bed_group_id',5)->count();
      $ipdBedOccupied = Bed::where('bed_group_id',5)->where('current_status','occupied')->count();
      $ipdBeds = [
         'total' => $ipdBeds,
         'occupied' => $ipdBedOccupied
      ];
      $avg_stay = PaymentBill::where('amount_for','Bed Charge')->avg('qty');
      $average_stay = round($avg_stay);
      return view('backend.admin.modules.report.report-dashboard',compact('consultantCount','appoinrmentsCount','totalRevenue','patientCounts','emergencyPatients','icuBeds','lab_count','ipdBeds','average_stay'));
    }
}
