<?php

namespace App\Http\Controllers\backend\admin\opdout;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicineCategory;
use App\Models\OpdoutVisit;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OpdoutController extends Controller
{
    public function index(){
        $users = User::where('usertype_id',2)->get(['id','firstname','lastname','room_number']);
        return view('backend.admin.modules.opdout.opd-out',compact('users'));
    }
   
    public function viewOpdOut(Request $request) {
    if($request->ajax()) {
        if($request->doctor_id != null && $request->roomNum != null){
            $appointment = Appointment::where('doctor_id', $request->doctor_id)
                                      ->where('room_number', $request->roomNum)
                                      ->get();
        } elseif ($request->doctor_id != null && $request->roomNum == null) {
            $appointment = Appointment::where('doctor_id', $request->doctor_id)->get();
        } elseif ($request->doctor_id == null && $request->roomNum != null) {
            $appointment = Appointment::where('room_number', $request->roomNum)->get();
        } else {
            $appointment = Appointment::get(); // **Load all records when no filters are applied**
        }

        return DataTables::of($appointment)
          
           ->addColumn('token',function($row){
                return '<a target="_blank" style="color: #859bff;" onclick="patientDetailsUsingToken('.$row->id.')">'.$row->token.'</a>';
            })
            ->addColumn('doctor',function($row){
                return "Dr. ".$row->user_data->firstname." ".$row->user_data->lastname;
            })
            ->addColumn('room_number',function($row){
                return $row->room_number;
            })
            ->addColumn('appointment_date',function($row){
                return $row->appointment_date;
            })
            ->addColumn('mobile',function($row){
                return $row->patient_data->mobile; //fetched through modal relationship
            })
            ->addColumn('gender',function($row){
                return $row->patient_data->gender;
            })
            ->addColumn('status',function($row){
                return $row->status;
            })
            ->addColumn('action',function($row){
               return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="iconamoon:eye-light" onclick="medicineDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="medicineEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="medicineDelete('.$row->id.')"></iconify-icon>
                         </a>';
            })
            ->rawColumns(['token','action'])
            ->make(true);
    }
}

function opdOutDetails($id){
    $patients = Patient::where('id',$id)->get();
    $appointments = Appointment::where('patient_id',$patients[0]->id)->get();
    $medicineCategory = MedicineCategory::where('status',1)->get();
    return view('backend.admin.modules.opdout.opd-out-details',compact('patients','appointments','medicineCategory'));
}
function opdOutVisitSubmit(Request $request){
   $validator = Validator::make($request->all(),[
            'patientId' => 'required',
            // 'symptomType' => 'nullable',
            'symptoms' => 'nullable',
            // 'symptomDesc' => 'nullable',
            'previousMedIssue' => 'nullable',
            'note' => 'nullable',
            'admissionDate' => 'nullable',
            // 'cases' => 'nullable',
            // 'casuality' => 'nullable',
            'oldPatient' => 'nullable',
            'reference' => 'nullable',
            'consultDoctor' => 'nullable',
            'chargeCategory' => 'required',
            'charge' => 'required',
            'discount' => 'nullable',
            'taxPer' => 'nullable',
            'amount' => 'required',
            'paymentMode' => 'nullable',
            'refNum' => 'nullable',
            'paidAmount' => 'nullable',
       ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }

        $optoutVisit = new OpdoutVisit();
        $optoutVisit->patient_id = $request->patientId;
        // $optoutVisit->symptomType = $request->symptomType;
        $optoutVisit->symptoms = $request->symptoms;
        // $optoutVisit->symptomDesc = $request->symptomDesc;
        $optoutVisit->previousMedIssue = $request->previousMedIssue;
        $optoutVisit->note = $request->note;
        $optoutVisit->admissionDate = $request->admissionDate;
        // $optoutVisit->cases = $request->cases;
        // $optoutVisit->casuality = $request->casuality;
        $optoutVisit->oldPatient = $request->oldPatient;
        $optoutVisit->consultDoctor = $request->consultDoctor;
        $optoutVisit->reference = $request->reference;
        $optoutVisit->chargeCategory = $request->chargeCategory;
        $optoutVisit->charge = $request->charge;
        $optoutVisit->discount = $request->discount;
        $optoutVisit->taxPer = $request->taxPer;
        $optoutVisit->amount = $request->amount;
        $optoutVisit->paymentMode = $request->paymentMode;
        $optoutVisit->refNum = $request->refNum;
        $optoutVisit->paidAmount = $request->paidAmount;

        if($optoutVisit->save()){
              return response()->json(['success'=>'Patient Visit added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Patient visit not added']);
        }

}
   
}



