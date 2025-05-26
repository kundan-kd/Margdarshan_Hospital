<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    public function index()
    {
        // Logic to retrieve and display a list of patients
        return view('backend.admin.modules.appointment.patient');
    }
    public function viewPatients(Request $request){
         if($request->ajax()){
        $appointment = Patient::get();
        return DataTables::of($appointment)
        ->addColumn('patient_id',function($row){
            return $row->patient_id;
        })
        ->addColumn('guardian_name',function($row){
            return $row->guardian_name;
        })
        ->addColumn('gender',function($row){
            return $row->gender; //fetched through modal relationship
        })
        ->addColumn('bloodtype',function($row){
            return $row->bloodtype;
        })
        ->addColumn('dob',function($row){
            return $row->dob;
        })
        ->addColumn('mstatus',function($row){
            return $row->marital_status;
        })
        ->addColumn('mobile',function($row){
            return $row->mobile;
        })
        ->addColumn('alt_mobile',function($row){
            return $row->alt_mobile;
        })
        ->addColumn('address',function($row){
            return $row->address;
        })
        ->addColumn('allergies',function($row){
            return $row->allergies;
        })
        // ->addColumn('status',function($row){
        //       $ischecked = $row->status == 1 ? 'checked':'';
        //         return '<div class="form-switch switch-primary">
        //                         <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
        //                     </div>';
        // })
        // ->addColumn('action',function($row){
        //     return '<!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
        //               <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
        //             </a> 
        //             <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
        //               <iconify-icon icon="lucide:edit" onclick="appointmentEdit('.$row->id.')"></iconify-icon>
        //             </a>-->
        //             <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
        //               <iconify-icon icon="mingcute:delete-2-line" onclick="patientDelete('.$row->id.')"></iconify-icon>
        //             </a>';
        // })
        ->rawColumns(['status','action'])
        ->make(true);
     }
    }
    public function deletePatientData(Request $request){
         Patient::where('id',$request->id)->delete();
        return response()->json(['success' => 'Patient Deleted Successfully'],200);
    }
}
