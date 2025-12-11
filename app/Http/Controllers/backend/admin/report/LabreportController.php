<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\LabInvestigation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LabreportController extends Controller
{
    public function index(){
        return view('backend.admin.modules.report.laboratory.lab');
    }
    public function labReportData(Request $request){
        if($request->ajax()){
            $labData = LabInvestigation::query();
            if($request->start_date && $request->end_date){
                $labData->whereBetween('created_at', [$request->start_date,$request->end_date]);
            }
            return DataTables::of($labData)
            ->addColumn('patient', function($row){
                return $row->patientData->patient_id.' ('.$row->patientData->name.')';
            })
            ->addColumn('test_type', function($row){
                return $row->testTypeData->name;
            })
            ->addColumn('test_name', function($row){
                return $row->testNameData->name;
            })
            ->addColumn('amount', function($row){
                return $row->amount;
            })
            ->addColumn('created_at', function($row){
                return $row->created_at->format('d-m-Y');
            })
            ->rawColumns(['patient','test_type','test_name'])
            ->make(true);
        }
    }
}
