<?php

namespace App\Http\Controllers\backend\admin\opdout;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Charge;
use App\Models\LabInvestigation;
use App\Models\Medication;
use App\Models\MedicineCategory;
use App\Models\OpdoutLabtest;
use App\Models\OpdoutMedicinedose;
use App\Models\OpdoutVisit;
use App\Models\Patient;
use App\Models\TestName;
use App\Models\TestType;
use App\Models\Timeline;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
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
        $doctorData = User::where('status',1)->get(['id','firstname','lastname','department_id']);
        $visitsData = Visit::where('patient_id',$patients[0]->id)->get();
        $medicationData = Medication::where('patient_id',$patients[0]->id)->get();
        $testtypes = TestType::where('status',1)->get();
        $testnames = TestName::where('status',1)->get();
        $labInvestigationData = LabInvestigation::where('patient_id',$patients[0]->id)->get();
        return view('backend.admin.modules.opdout.opd-out-details',compact('patients','appointments','medicineCategory','doctorData','visitsData','medicationData','testtypes','testnames','labInvestigationData'));
    }
    function opdOutVisitSubmit(Request $request){
        $validator = Validator::make($request->all(),[
                'patientId' => 'required',
                'symptoms' => 'nullable',
                'previousMedIssue' => 'nullable',
                'note' => 'nullable',
                'appointment_date' => 'nullable',
                'oldPatient' => 'nullable',
                'consultDoctor' => 'nullable',
                'charge' => 'required',
                'discount' => 'nullable',
                'taxPer' => 'nullable',
                'amount' => 'required',
                'paymentMode' => 'nullable',
                'refNum' => 'nullable',
                'paidAmount' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $optoutVisit = new Visit();
        $optoutVisit->type = "OPD";
        $optoutVisit->patient_id = $request->patientId;
        $optoutVisit->symptoms = $request->symptoms;
        $optoutVisit->previous_med_issue = $request->previousMedIssue;
        $optoutVisit->note = $request->note;
        $optoutVisit->appointment_date = $request->appointment_date;
        $optoutVisit->old_patient = $request->oldPatient;
        $optoutVisit->consult_doctor = $request->consultDoctor;
        $optoutVisit->charge = $request->charge;
        $optoutVisit->discount = $request->discount;
        $optoutVisit->tax_per = $request->taxPer;
        $optoutVisit->amount = $request->amount;
        $optoutVisit->payment_mode = $request->paymentMode;
        $optoutVisit->ref_num = $request->refNum;
        $optoutVisit->paid_amount = $request->paidAmount;

        if($optoutVisit->save()){
             $timelines = new Timeline();
             $timelines->type = "OPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "New Visit";
             $timelines->desc = "Appointment booked for opd on ".$request->appointment_date;
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'Patient Visit added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Patient visit not added']);
        }

    }
    public function viewOptOutVisit(Request $request){
    if($request->ajax()){
            $opdoutVisit = Visit::where('patient_id',$request->patient_id)->get();
            return DataTables::of($opdoutVisit)
            ->addColumn('visit_id',function($row){
                return 'MDVI0'.$row->id; //fetched through modal relationship
            })
            ->addColumn('appointment_date',function($row){
                return $row->appointment_date; //fetched through modal relationship
            })
            ->addColumn('doctor',function($row){
                return 'Dr. '.$row->doctorData->firstname.' '.$row->doctorData->lastname;
            })
            ->addColumn('symptons',function($row){
                return $row->symptoms;
            })
            ->addColumn('status',function($row){
                return $row->status;

            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#opd-out-visit-view" onclick="opdOutVisitViewData('.$row->id.')">
                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="lucide:edit" onclick="opdOutVisitEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="opdOutVisitDelete('.$row->id.')"></iconify-icon>
                        </a>';
            })
            ->rawColumns(['action'])       
            ->make(true);
        }
    }
    public function getOpdOutVisitData(Request $request){
        $getVisitDetails = Visit::where('id',$request->id)->get();
        $patientDetails = Patient::where('id',$getVisitDetails[0]->patient_id)->get();
        $getData = [
            'outVisitData' => $getVisitDetails,
            'outVisitPatientData' => $patientDetails
        ];
        return response()->json(['success'=>'opdout visit data fetched','data'=>$getData],200);
    }
    public function opdOutVisitDataUpdate(Request $request){
        $update = Visit::where('id',$request->id)->update([
            'symptoms' => $request->symptoms,
            'previous_med_issue' => $request->previousMedIssue,
            'note' => $request->note,
            'appointment_date' => $request->appointment_date,
            'oldPatient'=> $request->oldPatient,
            'consult_doctor' => $request->consultDoctor,
            'charge' => $request->charge,
            'discount' => $request->discount,
            'tax_per' => $request->taxPer,
            'amount' => $request->amount,
            'payment_mode' => $request->paymentMode,
            'ref_num' => $request->refNum,
            'paid_amount' => $request->paidAmount
        ]);
        if($update){
            return response()->json(['success'=>'Visit data updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Visit data not updated']);
        }
    }
    public function opdOutVisitDataDelete(Request $request){
        Visit::where('id',$request->id)->delete();
        return response()->json(['success'=>'Visit data deleted successfully'],200);
    }
    public function opdOutMedDataAdd(Request $request){
        $validator = Validator::make($request->all(),[
            'visitid' => 'required',
            'medCategory' => 'required',
            'medName' => 'required',
            'dose' => 'required',
            'remerks' => 'nullable',
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $medicineDose = new Medication();
        $medicineDose->type = "OPD";
        $medicineDose->patient_id = $request->patientId;
        $medicineDose->visit_id = $request->visitid;
        $medicineDose->medicine_category_id = $request->medCategory;
        $medicineDose->medicine_name_id = $request->medName;
        $medicineDose->dose = $request->dose;
        $medicineDose->remarks = $request->remerks; // Note: Fixed spelling from 'remerks' to 'remarks'
        if ($medicineDose->save()) {
             $timelines = new Timeline();
             $timelines->type = "OPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "Medicine Dose";
             $timelines->desc = "Medicine dose adviced";
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success' => 'Medicine dose added successfully'], 200);
        } else {
            return response()->json(['error_success' => 'Failed to add medicine dose'], 500);
        }

    }
    public function viewOptOutMedDose(Request $request){
          if($request->ajax()){
            $opdoutMedDose = Medication::where('patient_id',$request->patient_id)->get();
            return DataTables::of($opdoutMedDose)
            ->addColumn('visit_id',function($row){
                return 'MDVI0'.$row->visit_id; //fetched through modal relationship
            })
            ->addColumn('date',function($row){
                return $row->created_at; //fetched through modal relationship
            })
            ->addColumn('category',function($row){
                return $row->medicineCategoryData->name;
            })
            ->addColumn('name',function($row){
                return $row->medicineNameData->name;
            })
            ->addColumn('dose',function($row){
                return $row->dose;
            })
            ->addColumn('remarks',function($row){
                return $row->remarks;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="lucide:edit" onclick="opdOutMedDoseEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="opdOutMedDoseDelete('.$row->id.')"></iconify-icon>
                        </a>';
            })
            ->rawColumns(['action'])       
            ->make(true);
        }
    }
    public function getOpdOutMedDoseDetails(Request $request){
        $getData = Medication::where('id',$request->id)->get();
        return response()->json(['success'=>'opdout medication dose data fetched','data'=>$getData],200);
    }
    public function opdOutMedDataUpdate(Request $request){
        $update = Medication::where('id',$request->id)->update([
            'visit_id' => $request->visitid,
            'medicine_category_id' => $request->medCategory,
            'medicine_name_id' => $request->medName,
            'dose' => $request->dose,
            'remarks'=> $request->remerks
        ]);
        if($update){
            return response()->json(['success'=>'Medicine dose updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Medicine dose not updated']);
        }
    }
    public function opdOutMedDoseDataDelete(Request $request){
        Medication::where('id',$request->id)->delete();
        return response()->json(['success'=>'Medicine dose deleted successfully'],200);
    }
    public function opdOutLabSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'testType' => 'required',
            'testName' => 'required',
            'method' => 'nullable',
            'reportDays' => 'nullable',
            'testParameter' => 'nullable',
            'testRefRange' => 'nullable',
            'testUnit' => 'nullable',

        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $optoutLab = new LabInvestigation();
            $optoutLab->type = "OPD";
            $optoutLab->patient_id = $request->patientId;
            $optoutLab->test_type_id = $request->testType;
            $optoutLab->test_name_id = $request->testName;
            $optoutLab->method = $request->method;
            $optoutLab->report_days = $request->reportDays;
            $optoutLab->test_parameter = $request->testParameter;
            $optoutLab->test_ref_range = $request->testRefRange;
            $optoutLab->test_unit = $request->testUnit;
        if($optoutLab->save()){
            $timelines = new Timeline();
             $timelines->type = "OPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "Lab Test";
             $timelines->desc = "Lab Test Created";
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'Lab Test added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Lab Test not added']);
        }
    }
    public function viewOpdOutLabDetails(Request $request){
        if($request->ajax()){
            $labTestDetails = LabInvestigation::where('patient_id',$request->patient_id)->get();
            return DataTables::of($labTestDetails)
            ->addColumn('created_at',function($row){
                return $row->created_at;
            })
            ->addColumn('test_type',function($row){
                return $row->testTypeData->name;
            })
            ->addColumn('test_name',function($row){
                return $row->testNameData->name;
            })
            ->addColumn('report_date',function($row){
                $report_dt = Carbon::parse($row->created_at)->addDays($row->reportDays);
                return $report_dt;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#opd-lab-test-veiw" onclick="opdOutLabView('.$row->id.')">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="opdOutLabEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="opdOutLabDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getOpdOutLabData(Request $request){
        $getLabData = LabInvestigation::where('id',$request->id)->get();
        $patientData = Patient::where('id',$getLabData[0]->patient_id)->get();
        $testType = TestType::where('id',$getLabData[0]->test_type_id)->get();
        $testName = TestName::where('id',$getLabData[0]->test_name_id)->get();
        $data = [
               'labData' =>$getLabData, 
               'patientData' =>$patientData, 
               'testType' =>$testType, 
               'testName' =>$testName, 
        ];
        return response()->json(['success'=>'opdout lab data fetched','data'=>$data],200);
    }
    public function getOpdOutLabDetails(Request $request){
         $getData = LabInvestigation::where('id',$request->id)->get();
        return response()->json(['success'=>'opdout lab data fetched','data'=>$getData],200);
    }
    public function opdOutLabUpdateData(Request $request){
        $update = LabInvestigation::where('id',$request->id)->update([
            'test_type_id' => $request->testType,
            'test_name_id' => $request->testName,
            'method' => $request->method,
            'report_days' => $request->reportDays,
            'test_parameter'=> $request->testParameter,
            'test_ref_range' => $request->testRefRange,
            'test_unit' => $request->testUnit
        ]);
        if($update){
            return response()->json(['success'=>'Lab data updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Lab data not updated']);
        }
    }
    public function opdOutLabDataDelete(Request $request){
        LabInvestigation::where('id',$request->id)->delete();
        return response()->json(['success'=>'Lab test deleted successfully'],200);
    }
    public function opdOutChargeSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'amount' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $optoutCharge = new Charge();
            $optoutCharge->type = "OPD";
            $optoutCharge->patient_id = $request->patientId;
            $optoutCharge->name = $request->name;
            $optoutCharge->amount = $request->amount;
        if($optoutCharge->save()){
            $timelines = new Timeline();
             $timelines->type = "OPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "Charges";
             $timelines->desc = "Charges added for treatment or test";
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'Charge added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Charge not added']);
        }
    }
    public function viewOpdOutCharge(Request $request){
        if($request->ajax()){
            $labTestDetails = Charge::where('patient_id',$request->patient_id)->get();
            return DataTables::of($labTestDetails)
            ->addColumn('created_at',function($row){
                return $row->created_at;
            })
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('amount',function($row){
                return $row->amount;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="opdOutChargeEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="opdOutChargeDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getOpdOutChargeData(Request $request){
        $getData = Charge::where('id',$request->id)->get();
        return response()->json(['success'=>'Charge data fetched','data'=>$getData],200);
    }
    public function opdOutChargeDataUpdate(Request $request){
         $update = Charge::where('id',$request->id)->update([
            'name' => $request->name,
            'amount' => $request->amount
        ]);
        if($update){
            return response()->json(['success'=>'Charge updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Charge data not updated']);
        }
    }
    public function opdOutChargeDataDelete(Request $request){
        Charge::where('id',$request->id)->delete();
        return response()->json(['success'=>'Charge deleted successfully'],200);
    }
    public function opdOutVItalSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'value' => 'required',
            'date' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $optoutCharge = new Vital();
            $optoutCharge->type = "OPD";
            $optoutCharge->patient_id = $request->patientId;
            $optoutCharge->name = $request->name;
            $optoutCharge->value = $request->value;
            $optoutCharge->date = $request->date;
        if($optoutCharge->save()){
            $timelines = new Timeline();
             $timelines->type = "OPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "Vital";
             $timelines->desc = "Vital added of patient";
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'VItal added successfully'],200);
        }else{
            return response()->json(['error_success'=>'VItal not added']);
        }
    }
    public function viewOpdOutVital(Request $request){
          if($request->ajax()){
            $labTestDetails = Vital::where('patient_id',$request->patient_id)->get();
            return DataTables::of($labTestDetails)
            ->addColumn('date',function($row){
                return $row->date;
            })
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('value',function($row){
                return $row->value;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="opdOutVitalEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="opdOutVitalDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
   public function getOpdOutVitalData(Request $request){
        $getData = Vital::where('id',$request->id)->get();
        return response()->json(['success'=>'Vital data fetched','data'=>$getData],200);
   }
   public function opdOutVItalDataUpdate(Request $request){
         $update = Vital::where('id',$request->id)->update([
            'name' => $request->name,
            'value' => $request->value,
            'date' => $request->date
        ]);
        if($update){
            return response()->json(['success'=>'Vital updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Vital data not updated']);
        }
   }
    public function opdOutVitalDataDelete(Request $request){
        Vital::where('id',$request->id)->delete();
        return response()->json(['success'=>'Vital deleted successfully'],200);
    }
   
}



