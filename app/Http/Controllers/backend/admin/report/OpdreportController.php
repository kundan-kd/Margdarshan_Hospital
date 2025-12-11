<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\PatientLog;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OpdreportController extends Controller
{
    public function index(){
        return view('backend.admin.modules.report.opd.opd');
    }
    public function viewReport(Request $request){
        if($request->ajax()){
            $appointment = PatientLog::where('type','OPD')->orderBy('id','desc');
            if ($request->start_date && $request->end_date) {
                $appointment->whereBetween('appointment_date', [$request->start_date, $request->end_date]);
            }

            return DataTables::of($appointment)
            ->addColumn('opd_id',function($row){
                return 'MHAP0'.$row->id;
            })
            ->addColumn('name',function($row){
                return $row->patient_data->name;
            })
            ->addColumn('doa', function($row){
                return Carbon::parse($row->appointment_date)->format('d-m-Y');
            })
            ->addColumn('dov', function($row){
               $visit = Visit::where('appointment_id', $row->id)->first();
                if($visit && $visit->visited_date){
                    return Carbon::parse($visit->visited_date)->format('d-m-Y');
                }
                return '';
            })
            ->addColumn('consultant', function($row){
                return $row->user_data->name;
            })
            ->addColumn('fee', function($row){
                return $row->fee;
            })
            ->addColumn('p_status', function($row){
                return $row->payment_status;
            })
            ->addColumn('a_status', function($row){
                return $row->status;
            })
            ->addColumn('action', function($row){
                return ' <a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mdi:file-download-outline" onclick="printAppointmentBill(' . $row->id . ')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['opd_id','action'])
            ->make(true);
        }
    }
}
