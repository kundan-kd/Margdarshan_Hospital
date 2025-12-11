<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Vital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IcureportController extends Controller
{
    public function index(){
        return view('backend.admin.modules.report.icu.icu');
    }

    public function viewReport(Request $request)
    {
        if ($request->ajax()) {
            $icuPatientIds = Patient::where('type', 'ICU')->pluck('id'); //Get all ICU patient IDs
            $getVitals = Vital::whereIn('patient_id', $icuPatientIds)->get();// Get all vitals linked to those patients
            return DataTables::of($getVitals)
                ->addColumn('ip_no', function ($row) {
                    return $row->admit_id ?? ''; // assuming patient has ip_no
                })
                ->addColumn('name', function ($row) {
                    return $row->patientData->name ?? '';
                })
                ->addColumn('doa', function ($row) {
                    $doa = $row->patientData->admit_date;
                    return Carbon::parse($doa)->format('d-m-Y');
                })
                ->addColumn('vital', function ($row) {
                    return $row->name ?? ''; // adjust to your vital column
                })
                ->addColumn('range', function ($row) {
                    return $row->value ?? '';
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A');
                })
                ->make(true);
        }
    }
}
