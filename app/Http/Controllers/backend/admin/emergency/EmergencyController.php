<?php

namespace App\Http\Controllers\backend\admin\emergency;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmergencyController extends Controller
{
      public function index(){
        // $users = User::where('usertype_id',2)->get(['id','firstname','lastname','room_number']);
        return view('backend.admin.modules.emergency.emergency');
    }
     function emergencyDetails($id){
      // $patients = Patient::where('id',$id)->get();
      // $appointments = Appointment::where('patient_id',$patients[0]->id)->get();
      // $medicineCategory = MedicineCategory::where('status',1)->get();
      // $doctorData = User::where('status',1)->get(['id','firstname','lastname','department_id']);
            return view('backend.admin.modules.emergency.emergency-details');
    }
       public function viewPatients(Request $request){
        if($request->ajax()){
        $patients = Patient::where('type','EMERGENCY')->get();
        return DataTables::of($patients)
        ->addColumn('patient_id',function($row){
             return '<a target="_blank" style="color: #859bff;" onclick="emergencyPatientUsingId('.$row->id.')">'.$row->patient_id.'</a>';
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
        ->addColumn('mobile',function($row){
            return $row->mobile;
        })
        ->addColumn('allergies',function($row){
            return $row->known_allergies;
        })
        ->addColumn('status',function($row){
              $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
        })
        ->addColumn('action',function($row){
            return '<!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a> -->
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="emergencyPatientEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="emergencyPatientDelete('.$row->id.')"></iconify-icon>
                    </a>';
        })
        ->rawColumns(['patient_id','status','action'])
        ->make(true);
        }
    }
    public function addPatient(Request $request){
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
        $patient->type = "EMERGENCY";
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
            return response()->json(['success'=>'New IPD Patient added successfully'],201);
        }else{
            return response()->json(['error_success'=>'IPD Patient not added'],500);
        }
    }
    public function getEmergencyPatientData(Request $request){
       $getData = Patient::where('id',$request->id)->get();
        return response()->json(['success'=>'Emergency patient data fetched','data'=>$getData],200);
    }
    public function emergencyPatientDataUpdate(Request $request){
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
            return response()->json(['success'=>'Emergency patient updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Patient not updated']);
        }
    }
    public function emergencyPatientDataDelete(Request $request){
       Patient::where('id',$request->id)->delete();
        return response()->json(['success'=>'Patient data deleted successfully'],200);
    }
}
