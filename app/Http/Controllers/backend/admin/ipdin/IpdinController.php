<?php

namespace App\Http\Controllers\backend\admin\ipdin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\Charge;
use App\Models\LabInvestigation;
use App\Models\Medication;
use App\Models\MedicineCategory;
use App\Models\NurseNote;
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

class IpdinController extends Controller
{
     public function index(){
        return view('backend.admin.modules.ipdin.ipd-in');
    }
    public function ipdInPatientAdd(){
            return view('backend.admin.modules.ipdin.ipd-in-PatientAdd');
    }
    public function ipdInDetails($id){
        $patients = Patient::where('id',$id)->get();
        // $appointments = Appointment::where('patient_id',$patients[0]->id)->get();
        $medicineCategory = MedicineCategory::where('status',1)->get();
        $doctorData = User::where('status',1)->get(['id','name','department_id']);
        $visitsData = Visit::where('patient_id',$patients[0]->id)->get();
        $medicationData = Medication::where('patient_id',$patients[0]->id)->get();
        $testtypes = TestType::where('status',1)->get();
        $testnames = TestName::where('status',1)->get();
        $labInvestigationData = LabInvestigation::where('patient_id',$patients[0]->id)->get();
         $emergencyAvailBeds = Bed::where('bed_group_id',6)->where('current_status','vacant')->where('status',1)->get();
         $icuAvailBeds = Bed::where('bed_group_id',4)->where('current_status','vacant')->where('status',1)->get();
            return view('backend.admin.modules.ipdin.ipd-in-details',compact('patients','medicineCategory','doctorData','visitsData','medicationData','testtypes','testnames','labInvestigationData','emergencyAvailBeds','icuAvailBeds'));
    }
    public function viewPatients(Request $request){
        if($request->ajax()){
        $patients = Patient::where('type','IPD')->orWhere('type','ICU')->get();
        return DataTables::of($patients)
        ->addColumn('patient_id',function($row){
             return '<a target="_blank" class="text-primary cursor-pointer" onclick="ipdPatientUsingId('.$row->id.')">'.$row->patient_id.'</a>';
        })
        ->addColumn('department',function($row){
            return $row->type; //fetched through modal relationship
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
            //   $ischecked = $row->status == 1 ? 'checked':'';
            //     return '<div class="form-switch switch-primary">
            //                     <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
            //                 </div>';
             return 'Paid';    
        })
        ->addColumn('action',function($row){
            return '<!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a> -->
                     <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="ipdPatientEdit('.$row->id.');getBedData('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdpatientDelete('.$row->id.')"></iconify-icon>
                    </a>';
        })
        ->rawColumns(['patient_id','status','action'])
        ->make(true);
        }
    }
    public function getBedDetailsIpd(Request $request){
        $getData = Bed::where('id',$request->id)->get();
        $bedtypename = BedType::where('id',$getData[0]->bed_type_id)->get(['name']);
        return response()->json(['success'=>'Bed data fetched','data'=>$getData,'bedTypeName'=>$bedtypename],200);
    }
    public function getBedDataIpd(Request $request){
        $getAvailBed =  Bed::where('bed_group_id',5)->where('current_status','vacant')->where('status',1)->get(['id','bed_no']);
        $occupiedBed = '';
        $bedTypeName = '';
        if($request->id != null || $request->id != ''){
            $bed = Patient::where('id',$request->id)->get();
            $occupiedBed = Bed::where('id',$bed[0]->bed_id)->get();
            $bedTypeName = BedType::where('id',$occupiedBed[0]->bed_type_id)->get(['name']);
           
        }
        return response()->json(['success' => 'Bed data fetched successfully', 'data' => $getAvailBed,'bedData' => $occupiedBed,'bedType'=>$bedTypeName], 200);
         
    }
    public function addNewPatientIpd(Request $request){
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
            'allergy' => 'nullable',
            'bedNumId' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],422);
        }
        $now = Carbon::now();
        $month = date('m'); // Gets the current month (e.g., "05")
        $year = date('y'); // Gets the current year (e.g., "25")
        $patient = new Patient();
        $patient->type = "IPD";
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
        $patient->bed_id = $request->bedNumId;
        $patient->current_status = "Admitted";
        if($patient->save()){
            $patient->patient_id = "MHPT". $month.$year.$patient->id;
            $patient->save();
            Bed::where('id',$request->bedNumId)->update([
                'current_status' => 'occupied',
                'occupied_by_patient_id' => $patient->id,
                'occupied_date' => $now 
            ]);
            return response()->json(['success'=>'New IPD Patient added successfully'],201);
        }else{
            return response()->json(['error_success'=>'IPD Patient not added'],500);
        }
    }
      public function getIpdPatientData(Request $request){
       $getData = Patient::where('id',$request->id)->get();
        return response()->json(['success'=>'IPD patient data fetched','data'=>$getData],200);
    }
    public function ipdPatientDataUpdate(Request $request){
        $old_bed_id = Patient::where('id',$request->id)->get(['bed_id']);
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
            'address'=> $request->address,
            'bed_id'=> $request->bedNumId ?? NULL
        ]);
        if($update){
            $new_bed_id = Patient::where('id',$request->id)->get(['bed_id']);
            if($old_bed_id[0]->bed_id != $new_bed_id[0]->bed_id){
                Bed::where('id',$old_bed_id[0]->bed_id)->update([
                    'current_status' => 'vacant',
                    'occupied_by_patient_id' => NULL,
                    'occupied_date' => NULL
                ]);
                Bed::where('id',$new_bed_id[0]->bed_id)->update([
                    'current_status' => 'occupied',
                    'occupied_by_patient_id' => $request->id,
                    'occupied_date' => Carbon::now()
                ]);
            }// End of if condition for bed id change
            return response()->json(['success'=>'IPD patient updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Patient not updated']);
        }
    }
    public function ipdPatientDataDelete(Request $request){
       Patient::where('id',$request->id)->delete();
        return response()->json(['success'=>'Patient data deleted successfully'],200);
    }
     function moveToEmergencyStatus(Request $request){
        $now = Carbon::now();
        $previous_bed_data = Bed::where('occupied_by_patient_id',$request->id)->get();
        $curr_status = Patient::where('id',$request->id)->get(['type']);
        $bed_name = Bed::where('id',$request->bed_id)->get(['bed_no']);
        $update = Patient::where('id',$request->id)->update([
            'type' =>'EMERGENCY',
            'previous_type'=>$curr_status[0]->type
        ]);
        if($update){
            Bed::where('id',$request->bed_id)->update([
                'current_status' => 'occupied',
                'occupied_by_patient_id' => $request->id,
                'occupied_date' =>$now

            ]);
            Bed::where('id', $previous_bed_data[0]->id)->update([
                'previous_occupied_patient_id' => $previous_bed_data[0]->occupied_by_patient_id,
                'previous_occupied_date' => $previous_bed_data[0]->occupied_date,
                'occupied_by_patient_id' => null,
                'occupied_date' => null,
                'current_status' =>'vacant'
            ]);

            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->id;
            $timelines->title = "Moved to Emergency";
            $timelines->desc = "Moved to Emergency on bed ".$bed_name[0]->bed_no." from IPD";
            $timelines->created_by = "Admin";
            $timelines->save();
            return response()->json(['success'=>'Successfully moved to Emergency'],200);
        }
    }
    function moveToIcuStatus(Request $request){
        $now = Carbon::now();
        $previous_bed_data = Bed::where('occupied_by_patient_id',$request->id)->get();
        $curr_status = Patient::where('id',$request->id)->get(['type']);
        $bed_name = Bed::where('id',$request->bed_id)->get(['bed_no']);
        $update = Patient::where('id',$request->id)->update([
            'type' =>'ICU',
            'previous_type'=>$curr_status[0]->type
        ]);
        if($update){
            Bed::where('id',$request->bed_id)->update([
                'current_status' => 'occupied',
                'occupied_by_patient_id' => $request->id,
                'occupied_date' =>$now

            ]);
            Bed::where('id', $previous_bed_data[0]->id)->update([
                'previous_occupied_patient_id' => $previous_bed_data[0]->occupied_by_patient_id,
                'previous_occupied_date' => $previous_bed_data[0]->occupied_date,
                'occupied_by_patient_id' => null,
                'occupied_date' => null,
                'current_status' =>'vacant'
            ]);

            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->id;
            $timelines->title = "Moved to ICU";
            $timelines->desc = "Moved to ICU on bed ".$bed_name[0]->bed_no." from IPD";
            $timelines->created_by = "Admin";
            $timelines->save();
            return response()->json(['success'=>'Successfully moved to ICU'],200);
        }
    }
    public function patientDischargeStatus(Request $request){
        $update = Patient::where('id',$request->id)->update([
            'current_status' =>'Discharged'
        ]);
        if($update){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->id;
            $timelines->title = "Discharged";
            $timelines->desc = "Patient Discharged from IPD";
            $timelines->created_by = "Admin";
            $timelines->save();
            return response()->json(['success'=>'Successfully discharged from IPD'],200);
        }
    }
      function ipdVisitSubmit(Request $request){
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
        $optoutVisit->type = "IPD";
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
             $timelines->type = "IPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "New Visit";
             $timelines->desc = "Appointment booked for ipd on ".$request->appointment_date;
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'Patient Visit added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Patient visit not added']);
        }

    }
    public function viewIpdVisit(Request $request){
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
                return 'Dr. '.$row->doctorData->name;
            })
            ->addColumn('symptons',function($row){
                return $row->symptoms;
            })
            ->addColumn('status',function($row){
                return $row->status;

            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#ipd-in-visit-view" onclick="ipdVisitViewData('.$row->id.')">
                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="lucide:edit" onclick="ipdVisitEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="ipdVisitDelete('.$row->id.')"></iconify-icon>
                        </a>';
            })
            ->rawColumns(['action'])       
            ->make(true);
        }
    }
    public function getIpdVisitData(Request $request){
        $getVisitDetails = Visit::where('id',$request->id)->get();
        $patientDetails = Patient::where('id',$getVisitDetails[0]->patient_id)->get();
        $getData = [
            'ipdVisitData' => $getVisitDetails,
            'ipdVisitPatientData' => $patientDetails
        ];
        return response()->json(['success'=>'ipd visit data fetched','data'=>$getData],200);
    }
    public function ipdVisitDataUpdate(Request $request){
        $update = Visit::where('id',$request->id)->update([
            'symptoms' => $request->symptoms,
            'previous_med_issue' => $request->previousMedIssue,
            'note' => $request->note,
            'appointment_date' => $request->appointment_date,
            'old_patient'=> $request->oldPatient,
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
    public function ipdVisitDataDelete(Request $request){
        Visit::where('id',$request->id)->delete();
        return response()->json(['success'=>'Visit data deleted successfully'],200);
    }
        public function ipdMedDataAdd(Request $request){
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
        $medicineDose->type = "IPD";
        $medicineDose->patient_id = $request->patientId;
        $medicineDose->visit_id = $request->visitid;
        $medicineDose->medicine_category_id = $request->medCategory;
        $medicineDose->medicine_name_id = $request->medName;
        $medicineDose->dose = $request->dose;
        $medicineDose->remarks = $request->remerks; // Note: Fixed spelling from 'remerks' to 'remarks'
        if ($medicineDose->save()) {
             $timelines = new Timeline();
             $timelines->type = "IPD";
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
    public function viewIpdMedDose(Request $request){
          if($request->ajax()){
            $ipdMedDose = Medication::where('patient_id',$request->patient_id)->get();
            return DataTables::of($ipdMedDose)
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
                        <iconify-icon icon="lucide:edit" onclick="ipdMedDoseEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="ipdMedDoseDelete('.$row->id.')"></iconify-icon>
                        </a>';
            })
            ->rawColumns(['action'])       
            ->make(true);
        }
    }
    public function getIpdMedDoseDetails(Request $request){
        $getData = Medication::where('id',$request->id)->get();
        return response()->json(['success'=>'ipd medication dose data fetched','data'=>$getData],200);
    }
    public function ipdMedDataUpdate(Request $request){
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
    public function ipdMedDoseDataDelete(Request $request){
        Medication::where('id',$request->id)->delete();
        return response()->json(['success'=>'Medicine dose deleted successfully'],200);
    }
     public function ipdLabSubmit(Request $request){
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
        $ipdLab = new LabInvestigation();
            $ipdLab->type = "IPD";
            $ipdLab->patient_id = $request->patientId;
            $ipdLab->test_type_id = $request->testType;
            $ipdLab->test_name_id = $request->testName;
            $ipdLab->method = $request->method;
            $ipdLab->report_days = $request->reportDays;
            $ipdLab->test_parameter = $request->testParameter;
            $ipdLab->test_ref_range = $request->testRefRange;
            $ipdLab->test_unit = $request->testUnit;
        if($ipdLab->save()){
            $timelines = new Timeline();
             $timelines->type = "IPD";
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
    public function viewIpdLabDetails(Request $request){
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
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#ipd-lab-test-veiw" onclick="ipdLabView('.$row->id.')">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="ipdLabEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdLabDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getIpdLabData(Request $request){
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
        return response()->json(['success'=>'ipd lab data fetched','data'=>$data],200);
    }
    public function getIpdLabDetails(Request $request){
         $getData = LabInvestigation::where('id',$request->id)->get();
        return response()->json(['success'=>'ipd lab data fetched','data'=>$getData],200);
    }
    public function ipdLabUpdateData(Request $request){
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
    public function ipdLabDataDelete(Request $request){
        LabInvestigation::where('id',$request->id)->delete();
        return response()->json(['success'=>'Lab test deleted successfully'],200);
    }
     public function ipdChargeSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'amount' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $ipdCharge = new Charge();
            $ipdCharge->type = "IPD";
            $ipdCharge->patient_id = $request->patientId;
            $ipdCharge->name = $request->name;
            $ipdCharge->amount = $request->amount;
        if($ipdCharge->save()){
            $timelines = new Timeline();
             $timelines->type = "IPD";
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
    public function viewIpdCharge(Request $request){
        if($request->ajax()){
            $ipdCharges = Charge::where('patient_id',$request->patient_id)->get();
            return DataTables::of($ipdCharges)
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
                      <iconify-icon icon="lucide:edit" onclick="ipdChargeEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdChargeDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getIpdChargeData(Request $request){
        $getData = Charge::where('id',$request->id)->get();
        return response()->json(['success'=>'Charge data fetched','data'=>$getData],200);
    }
    public function ipdChargeDataUpdate(Request $request){
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
    public function ipdChargeDataDelete(Request $request){
        Charge::where('id',$request->id)->delete();
        return response()->json(['success'=>'Charge deleted successfully'],200);
    }
     public function ipdVItalSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'value' => 'required',
            'date' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $ipdVItals = new Vital();
            $ipdVItals->type = "IPD";
            $ipdVItals->patient_id = $request->patientId;
            $ipdVItals->name = $request->name;
            $ipdVItals->value = $request->value;
            $ipdVItals->date = $request->date;
        if($ipdVItals->save()){
            $timelines = new Timeline();
             $timelines->type = "IPD";
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
    public function viewIpdVital(Request $request){
          if($request->ajax()){
            $ipdVital = Vital::where('patient_id',$request->patient_id)->get();
            return DataTables::of($ipdVital)
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
                      <iconify-icon icon="lucide:edit" onclick="ipdVitalEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdVitalDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
   public function getIpdVitalData(Request $request){
        $getData = Vital::where('id',$request->id)->get();
        return response()->json(['success'=>'Vital data fetched','data'=>$getData],200);
   }
   public function ipdVItalDataUpdate(Request $request){
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
    public function ipdVitalDataDelete(Request $request){
        Vital::where('id',$request->id)->delete();
        return response()->json(['success'=>'Vital deleted successfully'],200);
    }
        public function ipdNurseNoteSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'nurseId' => 'required',
            'note' => 'required',
            'comment' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $ipdNurse = new NurseNote();
            $ipdNurse->type = "IPD";
            $ipdNurse->patient_id = $request->patientId;
            $ipdNurse->nurse_id = $request->nurseId;
            $ipdNurse->note = $request->note;
            $ipdNurse->comment = $request->comment;
        if($ipdNurse->save()){
            $timelines = new Timeline();
             $timelines->type = "IPD";
             $timelines->patient_id = $request->patientId;
             $timelines->title = "Nurse Note";
             $timelines->desc = "Nurse Note added of patient";
             $timelines->created_by = "Admin";
             $timelines->save();
            return response()->json(['success'=>'Nurse note added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Nurse note not added']);
        }
    }
    public function viewIpdNurseNote(Request $request){
          if($request->ajax()){
            $nurseNote = NurseNote::where('patient_id',$request->patient_id)->get();
            return DataTables::of($nurseNote)
            ->addColumn('date',function($row){
                return $row->created_at;
            })
            ->addColumn('name',function($row){
                return $row->nurseData->name;
            })
            ->addColumn('note',function($row){
                return $row->note;
            })
            ->addColumn('comment',function($row){
                return $row->comment;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="ipdNurseNoteEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdNurseNoteDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
   public function getIpdNurseNoteData(Request $request){
        $getData = NurseNote::where('id',$request->id)->get();
        return response()->json(['success'=>'Nurse note data fetched','data'=>$getData],200);
   }
   public function ipdNurseNoteDataUpdate(Request $request){
         $update = NurseNote::where('id',$request->id)->update([
            'nurse_id' => $request->nameId,
            'note' => $request->note,
            'comment' => $request->comment
        ]);
        if($update){
            return response()->json(['success'=>'Nurse note updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Nurse note data not updated']);
        }
   }
    public function ipdNurseDataDelete(Request $request){
        NurseNote::where('id',$request->id)->delete();
        return response()->json(['success'=>'Nurse note deleted successfully'],200);
    }
   
}
