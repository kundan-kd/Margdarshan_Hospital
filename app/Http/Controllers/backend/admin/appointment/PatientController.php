<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
            return $row->known_allergies;
        })
        ->addColumn('action',function($row){
            return '<!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a> -->
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="patientNewEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="patientNewDelete('.$row->id.')"></iconify-icon>
                    </a> -->';
        })
        ->rawColumns(['action'])
        ->make(true);
     }
    }
    public function deletePatientData(Request $request){
         Patient::where('id',$request->id)->delete();
        return response()->json(['success' => 'Patient Deleted Successfully'],200);
    }
    public function patientAddNewPatient(Request $request){
         $validator = Validator::make($request->all(),[
            'name' => 'required',
            'guardian_name' => 'required',
            'gender' => 'nullable',
            'bloodtype' => 'nullable',
            'dob' => 'required',
            'mstatus' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'alt_mobile' => 'nullable',
            'allergy' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],422);
        }
        $month = date('m'); // Gets the current month (e.g., "05")
        $year = date('y'); // Gets the current year (e.g., "25")
        $patient = new Patient();
        $patient->type = "OPD";
        $patient->name = $request->name;
        $patient->guardian_name = $request->guardian_name;
        $patient->gender = $request->gender;
        $patient->bloodtype = $request->bloodtype;
        $patient->dob = $request->dob;
        $patient->marital_status = $request->mstatus;
        $patient->mobile = $request->mobile;
        $patient->alt_mobile = $request->alt_mobile;
        $patient->known_allergies = $request->allergy;
        $patient->address = $request->address;
        if($patient->save()){
            $patient->patient_id = "MHPT". $month.$year.$patient->id;
            $patient->save();
            return response()->json(['success'=>'New Patient added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Patient not added'],500);
        }
    }
    public function newPatientData(Request $request){
        $getData = Patient::where('id',$request->id)->get();
        return response()->json(['success'=>'Patient details fetched successfully','data'=>$getData],200);
    }
    public function patientAddNewPatientDataUpdate(Request $request){
        $update = Patient::where('id',$request->id)->update([
            'name' => $request->name,
            'guardian_name' => $request->guardian_name,
            'gender' => $request->gender,
            'bloodtype' => $request->bloodtype,
            'dob'=> $request->dob,
            'marital_status'=> $request->mstatus,
            'mobile'=> $request->mobile,
            'alt_mobile'=> $request->alt_mobile,
            'known_allergies'=> $request->allergy,
            'address'=> $request->address
        ]);
        if($update){
            return response()->json(['success'=>'Patient updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Patient not updated']);
        }
    }
}
