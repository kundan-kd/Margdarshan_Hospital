<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\AdmitList;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IpdreportController extends Controller
{
    public function index(){
        return view('backend.admin.modules.report.ipd.consultant');
    }
    public function consultantReportData(Request $request){
        $consultant = Visit::where('type','!=','OPD')->whereNotNull('admit_id');
        if($request->start_date && $request->end_date){
            $consultant->whereBetween('created_at',[$request->start_date,$request->end_date]);
        }
        $consultant_data = $consultant->get();
        return DataTables::of($consultant_data)
        ->addColumn('doctor', function($row){
            return $row->doctorData->name;
        })
        ->addColumn('patient', function($row){
            return $row->patientData->patient_id. '('.$row->patientData->name.')';
        })
        ->addColumn('charge', function($row){
            return $row->amount;
        })
        ->addColumn('note', function($row){
            return $row->note;
        })
        ->addColumn('created_at', function($row){
            return $row->created_at->format('d-m-Y h:i A');
        })
        ->make(true);
    }
    public function billingReport(){
        return view('backend.admin.modules.report.ipd.patient-billing');
    }
    public function billingReportData(Request $request){
     $patient_data = AdmitList::with('patientData')
        ->where('type', '!=', 'EMERGENCY');

    if ($request->start_date && $request->end_date) {
        $patient_data->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    $patient_data = $patient_data->get();
    

    return DataTables::of($patient_data)
        ->addColumn('ip_no', fn($row) => 'MHAI' . $row->admit_id)
        ->addColumn('patient', fn($row) => $row->patientData->patient_id . ' (' . $row->patientData->name . ')')
        ->addColumn('type', fn($row) => $row->type)
        ->addColumn('doa', fn($row) => $row->created_at->format('d-m-Y h:i A'))
        ->addColumn('dod', fn($row) => $row->discharge_date ? Carbon::parse($row->discharge_date)->format('d-m-Y h:i A') : '')
        ->addColumn('consultant', fn($row) => $row->consultant ?? '') // adjust if you have consultant relation
        ->addColumn('bill', function ($row) {
            return PaymentBill::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('amount');
        })
        ->addColumn('paid', function ($row) {
            $received_amount = PaymentReceived::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('amount');

            $received_discount = PaymentReceived::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('discount_amount');

            return $received_amount + $received_discount;
        })
        ->addColumn('due', function ($row) {
            $bills = PaymentBill::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('amount');

            $received_amount = PaymentReceived::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('amount');

            $received_discount = PaymentReceived::where('patient_id', $row->patient_id)
                ->where('admit_id', $row->admit_id)
                ->sum('discount_amount');

            $received = $received_amount + $received_discount;

            return $bills - $received;
        })
      ->addColumn('action', function($row){
        $displayClass = $row->current_status == 'Discharged' ? '' : 'd-none';
        return '<td>
            <a href="javascript:void(0)" 
               title="Discharge Bill" 
               class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center '.$displayClass.'">
                <iconify-icon icon="mdi:file-download-outline" 
                              onclick="printBill('.$row->patient_id.', '.$row->admit_id.')">
                </iconify-icon>
            </a>
            <a href="javascript:void(0)" 
               title="Discharge Form" 
               class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center '.$displayClass.'">
                <iconify-icon icon="mdi:file-download-outline" 
                              onclick="dischargeFormPrint('.$row->patient_id.', '.$row->admit_id.')">
                </iconify-icon>
            </a>
        </td>';
    })
       ->rawColumns(['action'])
        ->make(true);
    }
}
