<?php

namespace App\Http\Controllers\backend\admin\ipdin;

use App\Http\Controllers\Controller;
use App\Models\AdmitList;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\BedType;
use App\Models\Charge;
use App\Models\LabInvestigation;
use App\Models\LabReport;
use App\Models\Lead;
use App\Models\Medication;
use App\Models\MedicineCategory;
use App\Models\NurseNote;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\TestName;
use App\Models\TestType;
use App\Models\Timeline;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Yajra\DataTables\Facades\DataTables;

class IpdinController extends Controller
{
    public function index(){
        $doctorData = User::where('status',1)->where('usertype_id',2)->get(['id','name','department_id']);
        return view('backend.admin.modules.ipdin.ipd-in',compact('doctorData'));
    }
    public function ipdInPatientAdd(){
            return view('backend.admin.modules.ipdin.ipd-in-PatientAdd');
    }
    public function ipdInDetails($id){
        $patients = Patient::where('id',$id)->get();
        $medicineCategory = MedicineCategory::where('status',1)->get();
        $doctorData = User::where('status',1)->where('usertype_id',2)->get(['id','name','department_id']);
        $nurseData = User::where('status',1)->where('usertype_id',3)->get(['id','name','department_id']);
        $visitsData = Visit::where('patient_id',$patients[0]->id)->get();
        $medicationData = Medication::with('medicineNameData')->where('patient_id',$patients[0]->id)->get();
        $testtypes = TestType::where('status',1)->get();
        $testnames = TestName::where('status',1)->get();
        $labInvestigationData = LabInvestigation::where('patient_id',$patients[0]->id)->get();
        $emergencyAvailBeds = Bed::where('bed_group_id',6)->where('current_status','vacant')->where('status',1)->get();
        $icuAvailBeds = Bed::where('bed_group_id',4)->where('current_status','vacant')->where('status',1)->get();
        $ipdAvailablelBeds = Bed::where('bed_group_id',5)->where('current_status','vacant')->where('status',1)->get();
        $admit_lists = AdmitList::with('patientData')->where('patient_id',$id)->orderBy('id','desc')->get();
        $timelines = Timeline::where('patient_id',$id)->where('title','Charges')->orderBy('id','desc')->get();
        return view('backend.admin.modules.ipdin.ipd-in-details',compact('patients','medicineCategory','doctorData','nurseData','visitsData','medicationData','testtypes','testnames','labInvestigationData','emergencyAvailBeds','icuAvailBeds','ipdAvailablelBeds','admit_lists','timelines'));
    }
    public function viewPatients(Request $request){
        if($request->ajax()){
        if($request->patientType != null ){
            $patients = Patient::where('type',$request->patientType)->orWhere('current_status',$request->patientType)->get();
        }else{
            $patients = Patient::whereIn('type', ['IPD', 'ICU'])->where('current_status','Admitted')->get();
        }    
        return DataTables::of($patients)
        ->addColumn('patient_id',function($row){
             return '<a target="_blank" class="text-primary cursor-pointer" onclick="ipdPatientUsingId('.$row->id.')">'.$row->patient_id.'</a>';
        })
        ->addColumn('department',function($row){
            return $row->type; //fetched through modal relationship
        })
        ->addColumn('bed_no',function($row){
            return $row->bedData->bed_no ?? 'NA'; //fetched through modal relationship
        })
        ->addColumn('gender',function($row){
            return $row->gender; //fetched through modal relationship
        })
        ->addColumn('entry_type',function($row){
            return $row->entry_type ?? 'NA'; //fetched through modal relationship
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
        ->addColumn('created_at',function($row){
            $date = new \DateTime($row->admit_date);
            $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
            return $date->format('d-m-Y h:i A');
        })
        ->addColumn('status',function($row){
            return $row->current_status === 'Discharged'? '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white">Discharged</span>': '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white" >Admitted</span>';   
        })
        ->addColumn('action', function($row) {
            $dischargeClass = ($row->current_status == 'Discharged') ? '' : 'd-none';
            return '<div class="d-flex gap-1">
            <a href="javascript:void(0)" title="Edit Patient" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="lucide:edit" onclick="ipdPatientEdit(' . $row->id . ');getBedData(' . $row->id . ')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" title="Discharge Bill" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center ' . $dischargeClass . '">
                    <iconify-icon icon="mdi:file-download-outline" onclick="printBill(' . $row->id . ','. $row->admit_id .')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" title="Admission Form" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mdi:file-download-outline" onclick="admissionForm(' . $row->id . ')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line" onclick="ipdpatientDelete(' . $row->id . ')"></iconify-icon>
                </a>-->
                </div>';
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
        return DB::transaction(function () use ($request) {
            if($request->id != ''){
                $oldPatientId = Patient::where('id',$request->id)->get(['patient_id']);
                $prevPatient = Patient::where('patient_id',$oldPatientId[0]->patient_id)->latest('id')->first();
                if($prevPatient->current_status == "Admitted"){
                    return response()->json(['previous_admitted'=>'Please discharge this patient from '.$prevPatient->type.' before adding new']);
                }
                // if($prevPatient->discharge_form_generated == 0){
                //     return response()->json(['discharge_form_generate_issue'=>'Please submit previous discharge summary before adding new']);
                // }
            }
            $check_prev_data = Patient::where('mobile',$request->mobile)->exists();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'guardian_name' => 'required',
                'gender' => 'nullable',
                'entry_type' => 'required',
                'bloodtype' => 'nullable',
                'dob' => 'required',
                'mstatus' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'consultDoctor' => 'nullable',
                'referPerson' => 'nullable',
                'alt_mobile' => 'nullable',
                'allergy' => 'nullable',
                'bedNumId' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=>$validator->errors()->all()],422);
            }
            $now = Carbon::now();
            $admit_id = time();
            if($check_prev_data){
                $pre_patient_data = Patient::where('mobile',$request->mobile)->get();
                Patient::where('id',$pre_patient_data[0]->id)->update([
                    'type' => 'IPD',
                    'previous_type' => $pre_patient_data[0]->type,
                    'type_change_date' => $now,
                    'admit_id' => $admit_id,
                    'bed_id' => $request->bedNumId,
                    'attended_doctor_id' => $request->consultDoctor,
                    'reference_person' => $request->referPerson,
                    'current_status' => 'Admitted',
                    'admit_date' => $now,
                    'discharge_date' => null,
                    'discharge_form_generated' => 0,
                    'discharge_type' => null
                ]);
                Bed::where('id',$request->bedNumId)->update([
                    'current_status' => 'occupied',
                    'occupied_by_patient_id' => $pre_patient_data[0]->id,
                    'occupied_date' => $now 
                ]);
                //if privious OPD status found Pending then make it cancelled
                PatientLog::where('patient_id',$pre_patient_data[0]->id)->whereNull('admit_id')->where('status','Pending')->update([
                    'admit_id' => '00000',
                    'status'   => 'Cancelled',
                    'description' => 'Revisit direct admit in IPD'
                ]);

                $patient_logs = new PatientLog();
                $patient_logs->patient_id = $pre_patient_data[0]->id;
                $patient_logs->admit_id = $admit_id;
                $patient_logs->type = "IPD";
                $patient_logs->bed_id = $request->bedNumId;
                $patient_logs->doctor_id = $request->consultDoctor;
                $patient_logs->reference_person = $request->referPerson;
                $patient_logs->current_status = "Admitted";
                if($patient_logs->save()){
                    $payment_bills = new PaymentBill();
                    $payment_bills->type = "IPD";
                    $payment_bills->patient_id = $pre_patient_data[0]->id;
                    $payment_bills->admit_id = $admit_id;
                    $payment_bills->to_bed_id = $request->bedNumId;
                    $payment_bills->amount_for = 'Bed Charge';
                    $payment_bills->title = 'Patient Admitted to IPD';  // amount is updated here when move to other dept or discharge
                    $payment_bills->save();

                    //store admit_id for further patient revisit refrences
                    $admit_data = new AdmitList();   
                    $admit_data->patient_id = $pre_patient_data[0]->id;
                    $admit_data->admit_id = $admit_id;
                    $admit_data->type = "IPD";
                    $admit_data->current_status = "Admitted";
                    $admit_data->desc = "Patient revisit in IPD";
                    $admit_data->save();

                    $timelines = new Timeline();
                    $timelines->type = "IPD";
                    $timelines->patient_id = $pre_patient_data[0]->id;
                    $timelines->admit_id = $admit_id;
                    $timelines->title = "Patient Admitted";
                    $timelines->desc = "Patient admitted to IPD";
                    $timelines->bed_id = $request->bedNumId;
                    $timelines->created_by = Auth::id();
                    $timelines->save();
                    return response()->json(['success'=>'New IPD Patient added successfully','data'=>$pre_patient_data[0]->id],200);
                }else{
                    return response()->json(['error_success'=>'IPD Patient not added'],500);
                }
            }else{
                $month = date('m'); // Gets the current month (e.g., "05")
                $year = date('y'); // Gets the current year (e.g., "25")
                $patient = new Patient();
                $patient->type = "IPD";
                $patient->admit_id = $admit_id;
                $patient->name = $request->name;
                $patient->guardian_name = $request->guardian_name;
                $patient->gender = $request->gender;
                $patient->entry_type = $request->entry_type;
                $patient->bloodtype = $request->bloodtype;
                $patient->dob = $request->dob;
                $patient->marital_status = $request->mstatus;
                $patient->mobile = $request->mobile;
                $patient->alt_mobile = $request->alt_mobile;
                $patient->known_allergies = $request->allergy;
                $patient->address = $request->address;
                $patient->attended_doctor_id = $request->consultDoctor;
                $patient->reference_person = $request->referPerson;
                $patient->bed_id = $request->bedNumId;
                $patient->current_status = "Admitted";
                $patient->admit_date = $now;
                if($patient->save()){
                    // if($request->id != ''){
                    //     $oldPatientId = Patient::where('id',$request->id)->get(['patient_id','barcode']);
                    //     $patient->patient_id = $oldPatientId[0]->patient_id;
                    //     $patient->barcode = $oldPatientId[0]->barcode;
                    //     $patient->save();
                    // }else{
                        $patient->patient_id = "MHPT". $month.$year.$patient->id;
                        $patient->save();
                        //generate bar code
                        $generator = new BarcodeGeneratorPNG();
                        $barcode = $generator->getBarcode($patient->patient_id, $generator::TYPE_CODE_128);
                        if ($barcode) {
                            //generate barcode and store in storage/public/barcode
                                $fileName = $patient->patient_id.'.' . time() . '.png';
                                $path = public_path('backend/uploads/barcode/' . $fileName);
                                file_put_contents($path, $barcode);
                                $patient->barcode = $fileName; //store barcode name in database
                                $patient->save();
                        } 
                        //generate bar code end
                    // }
                    //lead convert when same mobile number patient get admitted
                    $lead = Lead::where('mobile', $request->mobile)->where('lead_status', 'Pending')->whereNotNull('assign_to')->first();     
                    if ($lead) {
                        $lead->lead_status = 'Converted';
                        $lead->lead_patient_id = $patient->id;
                        $lead->lead_status_date = now();
                        $lead->save();

                        $patient->lead_id = $lead->id;
                        $patient->save();
                    }   
                    Bed::where('id',$request->bedNumId)->update([
                        'current_status' => 'occupied',
                        'occupied_by_patient_id' => $patient->id,
                        'occupied_date' => $now 
                    ]);
                    $patient_logs = new PatientLog();
                    $patient_logs->patient_id = $patient->id;
                    $patient_logs->admit_id = $admit_id;
                    $patient_logs->type = "IPD";
                    $patient_logs->bed_id = $request->bedNumId;
                    $patient_logs->doctor_id = $request->consultDoctor;
                    $patient_logs->reference_person = $request->referPerson;
                    $patient_logs->current_status = "Admitted";
                    $patient_logs->save();

                    $payment_bills = new PaymentBill();
                    $payment_bills->type = "IPD";
                    $payment_bills->patient_id = $patient->id;
                    $payment_bills->admit_id = $admit_id;
                    $payment_bills->to_bed_id = $request->bedNumId;
                    $payment_bills->amount_for = 'Bed Charge';
                    $payment_bills->title = 'Patient Admitted to IPD';  // amount is updated here when move to other dept or discharge
                    $payment_bills->save();

                    //store admit_id for further patient revisit refrences
                    $admit_data = new AdmitList();   
                    $admit_data->patient_id = $patient->id;
                    $admit_data->admit_id = $admit_id;
                    $admit_data->type = "IPD";
                    $admit_data->current_status = "Admitted";
                    $admit_data->desc = "New patient added from IPD";
                    $admit_data->save();

                    $timelines = new Timeline();
                    $timelines->type = "IPD";
                    $timelines->patient_id = $patient->id;
                    $timelines->admit_id = $admit_id;
                    $timelines->title = "Patient Added";
                    $timelines->desc = "New patient admitted to IPD";
                    $timelines->bed_id = $request->bedNumId;
                    $timelines->created_by = Auth::id();
                    $timelines->save();
                    return response()->json(['success'=>'New IPD Patient added successfully','data'=>$patient->id],200);
                }else{
                    return response()->json(['error_success'=>'IPD Patient not added'],500);
                }
            }   
        });
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
            'entry_type' => $request->entry_type,
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

    function moveToIcuStatus(Request $request){
        return DB::transaction(function () use ($request) {
            $latest_patient_log = PatientLog::where('patient_id',$request->id)->latest()->first();
            $previous_payment_bill = PaymentBill::where('patient_id', $request->id)->where('amount_for', 'Bed Charge')->latest('id')->first();
            $last_bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->get(); // Get the actual amount value
            $old_bed_priority = BedGroup::where('id', $last_bed_amount[0]->bed_group_id)->get();
            $new_bed_amount = Bed::where('id',$request->bed_id)->get(); // Get the actual amount value
            $new_bed_priority = BedGroup::where('id', $new_bed_amount[0]->bed_group_id)->get();
            $now = Carbon::now();
            $previous_bed_data = Bed::where('occupied_by_patient_id',$request->id)->get();
            $curr_status = Patient::where('id',$request->id)->get(['type','admit_id']);
            $bed_name = Bed::where('id',$request->bed_id)->get(['bed_no']);
            $update = Patient::where('id',$request->id)->update([
                'type' =>'ICU',
                'bed_id' => $request->bed_id,
                'previous_type'=>$curr_status[0]->type,
                'type_change_date' => $now
            ]);
            if($update){
                $patient_logs = new PatientLog();
                $patient_logs->patient_id = $request->id;
                $patient_logs->admit_id =  $curr_status[0]->admit_id;
                $patient_logs->type = 'ICU';
                $patient_logs->bed_id = $request->bed_id;
                $patient_logs->doctor_id = $latest_patient_log->doctor_id;
                $patient_logs->reference_person = $latest_patient_log->reference_person;
                $patient_logs->current_status = "Moved";
                $patient_logs->description = "Movement IPD to ICU";
                $patient_logs->save();

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
                $payment_bills = new PaymentBill();
                $payment_bills->type = "IPD";
                $payment_bills->patient_id = $request->id;
                $payment_bills->admit_id =  $curr_status[0]->admit_id;
                $payment_bills->to_bed_id = $request->bed_id;
                $payment_bills->amount_for = 'Bed Charge';
                $payment_bills->title = 'Patient Moved to ICU';
                $payment_bills->save();
                // $new_created_at = $payment_bills->created_at;
                $occupied_days = 0;
                $pre_bed_amount = 0;
                // if ($previous_payment_bill) {
                //   $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
                //     $admitTime = new DateTime($previous_payment_bill->created_at);
                //     $dischargeTime = new DateTime();
                //     $checkIn  = Carbon::parse($admitTime);   // e.g. 2025-09-01 13:45:18
                //     $checkOut = Carbon::parse($dischargeTime); // e.g. 2025-09-03 14:45:18
                //     $cutoffHour = 14;
                //     $days = 0;
                //     // Case 1: If check-in before 2 PM, count first day
                //     if ($checkIn->hour < $cutoffHour) {
                //         $days++;
                //     }
                //     // Case 2: Count full 24-hour blocks between check-in and check-out
                //     $days += $checkIn->diffInDays($checkOut);
                //     if ($days > 0 && $days < 1) {
                //         $days = 1;
                //     }
                
                //     // Case 3: If checkout is after 2 PM, add an extra day
                //     if ($checkOut->hour >= $cutoffHour) {
                //         $days++;
                //     }
                //     $occupied_days = (int)$days;

                //     //if same day IPD to ICU move then higher amount will be added
                //         if(((int)$days ==0) && ($new_bed_priority[0]->priority < $old_bed_priority[0]->priority)){
                //             $pre_bed_amount =  $new_bed_amount[0]->amount * $occupied_days;
                //         }else{
                //             $pre_bed_amount = $last_bed_amount[0]->amount * $occupied_days;
                //         }
                //         PaymentBill::where('id',$previous_payment_bill->id)->update([
                //             'days' => $occupied_days,
                //             'qty' => $occupied_days,
                //             'amount' => $pre_bed_amount
                //         ]);
                // } // amount add to previous bed type for billing
                
                
                
                
                if ($previous_payment_bill && ($previous_payment_bill->amount == 0 || $previous_payment_bill->amount === NULL)) {
                $admitTime = Carbon::parse($previous_payment_bill->created_at);
                $dischargeTime = Carbon::now(); // Or use actual discharge time if available
                $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first();
                $cutoffHour = 14;
                $days = 0;
                // Case 1: If admitted before 2 PM, count the admission day
                 if ($admitTime->hour < $cutoffHour) {
                $days++;
                }
                 // Start from first 2 PM after admission
                $current = $admitTime->copy()->setTime($cutoffHour, 0);
                 // Loop through each 2 PM until discharge
                while ($current <= $dischargeTime) {
                    $days++;
                    $current->addDay(); // move to next day's 2 PM
                }
                // Ensure at least 1 day
                if ($days < 1) {
                    $days = 1;
                }
                $occupied_days = $days;
                $pre_bed_amount = $bed_amount * $occupied_days;
                    PaymentBill::where('id',$previous_payment_bill->id)->update([
                        'days' => $occupied_days,
                        'qty' => $occupied_days,
                        'amount' => $pre_bed_amount
                    ]);
            }
                
                
                
                
                
                $timelines = new Timeline();
                $timelines->type = "IPD";
                $timelines->patient_id = $request->id;
                $timelines->admit_id = $curr_status[0]->admit_id;
                $timelines->title = "Moved to ICU";
                $timelines->desc = "Moved to ICU on bed ".$bed_name[0]->bed_no." from IPD";
                $timelines->bed_id = $request->bed_id;
                $timelines->created_by = Auth::id();
                $timelines->save();
                return response()->json(['success'=>'Successfully moved to ICU'],200);
            }
        });
    }
    public function moveToIpdStatusFromIcu(Request $request){
        return DB::transaction(function () use ($request) {
            $latest_patient_log = PatientLog::where('patient_id',$request->id)->latest()->first();
            $previous_payment_bill = PaymentBill::where('patient_id', $request->id)->where('amount_for', 'Bed Charge')->latest('id')->first();// Get the most recent 'Bed Charge' payment bill for a specific patient
            $last_bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->get(); // Get the actual amount value
            $old_bed_priority = BedGroup::where('id', $last_bed_amount[0]->bed_group_id)->get();
            $new_bed_amount = Bed::where('id',$request->bed_id)->get(); // Get the actual amount value
            $new_bed_priority = BedGroup::where('id', $new_bed_amount[0]->bed_group_id)->get();
            $now = Carbon::now();
            $previous_bed_data = Bed::where('occupied_by_patient_id',$request->id)->get();
            $curr_status = Patient::where('id',$request->id)->get(['type','admit_id']);
            $bed_name = Bed::where('id',$request->bed_id)->get(['bed_no']);
            $update = Patient::where('id',$request->id)->update([
                'type' =>'IPD',
                'bed_id' => $request->bed_id,
                'previous_type'=>$curr_status[0]->type,
                'type_change_date' => $now
            ]);
            if($update){
                $patient_logs = new PatientLog();
                $patient_logs->patient_id = $request->id;
                $patient_logs->admit_id =  $curr_status[0]->admit_id;
                $patient_logs->type = 'IPD';
                $patient_logs->bed_id = $request->bed_id;
                $patient_logs->doctor_id = $latest_patient_log->doctor_id;
                $patient_logs->reference_person = $latest_patient_log->reference_person;
                $patient_logs->current_status = "Moved";
                $patient_logs->description = "Movement ICU to IPD";
                $patient_logs->save();

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
                $payment_bills = new PaymentBill();
                $payment_bills->type = "ICU";
                $payment_bills->patient_id = $request->id;
                $payment_bills->admit_id =  $curr_status[0]->admit_id;
                $payment_bills->to_bed_id = $request->bed_id;
                $payment_bills->amount_for = 'Bed Charge';
                $payment_bills->title = 'Patient Moved to IPD';
                $payment_bills->save();
                // $new_created_at = $payment_bills->created_at;
                $occupied_days = 0;
                $pre_bed_amount = 0;
                // if ($previous_payment_bill) {
                    
                //   $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
                //     $admitTime = new DateTime($previous_payment_bill->created_at);
                //     $dischargeTime = new DateTime();
                //     $checkIn  = Carbon::parse($admitTime);   // e.g. 2025-09-01 13:45:18
                //     $checkOut = Carbon::parse($dischargeTime); // e.g. 2025-09-03 14:45:18
                //     $cutoffHour = 14;
                //     $days = 0;
                //     // Case 1: If check-in before 2 PM, count first day
                //     if ($checkIn->hour < $cutoffHour) {
                //         $days++;
                //     }
                //     // Case 2: Count full 24-hour blocks between check-in and check-out
                //     $days += $checkIn->diffInDays($checkOut);
                //      if ($days > 0 && $days < 1) {
                //         $days = 1;
                //     }
                    
                
                //     // Case 3: If checkout is after 2 PM, add an extra day
                //     if ($checkOut->hour >= $cutoffHour) {
                //         $days++;
                //     }
                //     $occupied_days = (int)$days;
                    
                    
                // //if same day IPD to ICU move then higher amount will be added
                //         if(((int)$days ==0) && ($new_bed_priority[0]->priority < $old_bed_priority[0]->priority)){
                //             $pre_bed_amount =  $new_bed_amount[0]->amount * $occupied_days;
                //         }else{
                //             $pre_bed_amount = $last_bed_amount[0]->amount * $occupied_days;
                //         }
                //         PaymentBill::where('id',$previous_payment_bill->id)->update([
                //             'days' => $occupied_days,
                //             'qty' => $occupied_days,
                //             'amount' => $pre_bed_amount
                //         ]);
                
                // } // amount add to previous bed type for billing
                
                
                
                
                
                
                
                 if ($previous_payment_bill && ($previous_payment_bill->amount == 0 || $previous_payment_bill->amount === NULL)) {
                $admitTime = Carbon::parse($previous_payment_bill->created_at);
                $dischargeTime = Carbon::now(); // Or use actual discharge time if available
                $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first();
                $cutoffHour = 14;
                $days = 0;
                // Case 1: If admitted before 2 PM, count the admission day
                if ($admitTime->hour < $cutoffHour) {
                $days++;
                }
                 // Start from first 2 PM after admission
                $current = $admitTime->copy()->setTime($cutoffHour, 0);
                 // Loop through each 2 PM until discharge
                while ($current <= $dischargeTime) {
                    $days++;
                    $current->addDay(); // move to next day's 2 PM
                }
                // Ensure at least 1 day
                if ($days < 1) {
                    $days = 1;
                }
                $occupied_days = $days;
                $pre_bed_amount = $bed_amount * $occupied_days;
                    PaymentBill::where('id',$previous_payment_bill->id)->update([
                        'days' => $occupied_days,
                        'qty' => $occupied_days,
                        'amount' => $pre_bed_amount
                    ]);
            }
                
                
                
                
                
                
                

                $timelines = new Timeline();
                $timelines->type = "ICU";
                $timelines->patient_id = $request->id;
                $timelines->admit_id = $curr_status[0]->admit_id;
                $timelines->title = "Moved to IPD";
                $timelines->desc = "Moved to IPD on bed ".$bed_name[0]->bed_no." from ICU";
                $timelines->bed_id = $request->bed_id;
                $timelines->created_by = Auth::id();
                $timelines->save();
                return response()->json(['success'=>'Successfully moved to IPD'],200);
            }
        });
    }
    public function calculateDischargeAmount(Request $request){
        $bill_amount = PaymentBill::where('patient_id',$request->id)->where('status',NULL)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$request->id)->where('status',NULL)->sum('amount');
        return response()->json(['success'=>'Discharge amount calculated successfully','bill_amount'=>$bill_amount,'received_amount'=>$received_amount],200);
    }
    public function submitRestIpdAmount(Request $request){
         // Insert payment received details
            if($request->payAmount > 0){
                $payment_received = new PaymentReceived();
                $payment_received->patient_id = $request->patient_id;
                $payment_received->type = 'IPD';
                $payment_received->amount_for = 'Discharge Amount';
                $payment_received->title = 'Discharge Amount Received';
                $payment_received->amount = $request->payAmount;
                if($payment_received->save()){
                    return response()->json(['success'=>'Discharge amount submitted'],200);
                }else{
                    return response()->json(['error_success'=>'Discharge amount not submitted']);
                }
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
            $timelines->created_by = Auth::id();
            $timelines->save();
            return response()->json(['success'=>'Successfully discharged from IPD'],200);
        }
    }
        function ipdVisitSubmit(Request $request){
            $validator = Validator::make($request->all(),[
                'patientId' => 'required',
                'consultDoctor' => 'nullable',
                'charge' => 'required',
                'discount' => 'nullable',
                'taxPer' => 'nullable',
                'amount' => 'required',
                'desc' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $optoutVisit = new Visit();
        $optoutVisit->type = "IPD";
        $optoutVisit->patient_id = $request->patientId;
        $optoutVisit->admit_id = $admit_id;
        $optoutVisit->consult_doctor = $request->consultDoctor;
        $optoutVisit->charge = $request->charge;
        $optoutVisit->discount = $request->discount;
        $optoutVisit->tax_per = $request->taxPer;
        $optoutVisit->amount = $request->amount;
        $optoutVisit->note = $request->desc;

        if($optoutVisit->save()){
            $payment_bills = new PaymentBill();
            $payment_bills->type = "IPD";
            $payment_bills->patient_id = $request->patientId;
            $payment_bills->admit_id = $admit_id;
            $payment_bills->amount_for = 'IPD Visit';
            $payment_bills->title = 'Doctor New Visit';
            $payment_bills->amount = $request->amount;
            $payment_bills->save();

            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->patientId;
            $timelines->admit_id = $admit_id;
            $timelines->title = "IPD Visit";
            $timelines->desc = "Doctor visited to IPD Patient";
            $timelines->created_by = Auth::id();
            $timelines->save();
            return response()->json(['success'=>'Doctor Visit added successfully'],200);
        }else{
            return response()->json(['error_success'=>'visit not added']);
        }
    }
    public function viewIpdVisit(Request $request){
    if($request->ajax()){
            $opdoutVisit = Visit::where('patient_id',$request->patient_id)->get();
            return DataTables::of($opdoutVisit)
            ->addColumn('visit_id',function($row){
                return 'MDVI0'.$row->id; //fetched through modal relationship
            })
            ->addColumn('Type',function($row){
                return $row->type; //fetched through modal relationship
            })
            ->addColumn('admit_id',function($row){
                $admit = $row->admit_id == null ? 'Not Admitted' : 'MDVI'.$row->admit_id;
                return $admit; //fetched through modal relationship
            })
            ->addColumn('visit_date',function($row){
                return $row->created_at
                ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y h:i A');      // Format as human-readable
            })
            ->addColumn('doctor',function($row){
                return 'Dr. '.$row->doctorData->name;
            })
            ->addColumn('desc',function($row){
                return $row->note;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#ipd-in-visit-view" onclick="ipdVisitViewData('.$row->id.')">
                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="lucide:edit" onclick="ipdVisitEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="ipdVisitDelete('.$row->id.')"></iconify-icon>
                        </a>-->';
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
            'consult_doctor' => $request->consultDoctor,
            'charge' => $request->charge,
            'discount' => $request->discount,
            'tax_per' => $request->taxPer,
            'amount' => $request->amount,
            'note' => $request->desc
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
    public function ipdVisitId(Request $request){
        $visitId = Visit::where('patient_id',$request->id)->orderBy('id','desc')->get();
        if($visitId){
            return response()->json(['success'=>'Visit id fetched successfully','data'=>$visitId],200);
        }else{
            return response()->json(['error_success'=>'Visit id not found'],404);
        }
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
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $medicineDose = new Medication();
        $medicineDose->type = "IPD";
        $medicineDose->patient_id = $request->patientId;
        $medicineDose->admit_id = $admit_id;
        $medicineDose->visit_id = $request->visitid;
        $medicineDose->medicine_category_id = $request->medCategory;
        $medicineDose->medicine_name_id = $request->medName;
        $medicineDose->dose = $request->dose;
        $medicineDose->remarks = $request->remerks; // Note: Fixed spelling from 'remerks' to 'remarks'
        if ($medicineDose->save()) {
             $timelines = new Timeline();
             $timelines->type = "IPD";
             $timelines->patient_id = $request->patientId;
             $timelines->admit_id = $admit_id;
             $timelines->title = "Medicine Dose";
             $timelines->desc = "Medicine dose adviced";
             $timelines->created_by = Auth::id();
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
                return $row->created_at
                ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y h:i A');//fetched through modal relationship
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
                        <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line" onclick="ipdMedDoseDelete('.$row->id.')"></iconify-icon>
                        </a>-->';
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
    public function getTestNameByType(Request $request){
        $testTypes = TestName::where('test_type_id',$request->id)->where('status',1)->get(['id','name']);
        return response()->json(['success'=>'Test names fetched successfully','data'=>$testTypes],200);
    }
    public function getTestDetailsById(Request $request){
        $testDetails = TestName::where('id',$request->id)->get();
            return response()->json(['success'=>'Test details fetched successfully','data'=>$testDetails],200);
    }
    public function ipdLabSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'testType' => 'required',
            'testName' => 'required',
            'method' => 'nullable',
            'amount' => 'nullable',
            'reportDays' => 'nullable',
            'testParameter' => 'nullable',
            'testRefRange' => 'nullable',
            'testUnit' => 'nullable',

        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $ipdLab = new LabInvestigation();
        $ipdLab->type = "IPD";
        $ipdLab->patient_id = $request->patientId;
        $ipdLab->admit_id = $admit_id;
        $ipdLab->test_type_id = $request->testType;
        $ipdLab->test_name_id = $request->testName;
        $ipdLab->method = $request->method;
        $ipdLab->amount = $request->amount;
        $ipdLab->report_days = $request->reportDays;
        $ipdLab->test_parameter = $request->testParameter;
        $ipdLab->test_ref_range = $request->testRefRange;
        $ipdLab->test_unit = $request->testUnit;
        if($ipdLab->save()){
            $payment_bills = new PaymentBill(); 
            $payment_bills->type = "IPD";
            $payment_bills->type_id = $ipdLab->id;
            $payment_bills->patient_id = $request->patientId;
            $payment_bills->admit_id = $admit_id;
            $payment_bills->amount_for = 'Lab';
            $payment_bills->title  = $ipdLab->testTypeData->name . ' (' . $ipdLab->testNameData->name . ')';
            $payment_bills->qty = 1;
            $payment_bills->amount = $request->amount;
            $payment_bills->save();

            $timelines = new Timeline();
             $timelines->type = "IPD";
             $timelines->patient_id = $request->patientId;
             $timelines->admit_id = $admit_id;
             $timelines->title = "Lab Test";
             $timelines->desc = "Lab Test Created";
             $timelines->created_by = Auth::id();
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
                return $row->created_at
                ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y h:i A');      // Format as human-readable
            })
            ->addColumn('admit_id',function($row){
               return 'MHAI'.$row->admit_id;
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
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mdi:file-upload-outline" onclick="uploadPdf('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="ipdLabEdit('.$row->id.');getTestName('.$row->test_type_id.','.$row->test_name_id.');getTestDetails('.$row->test_name_id.')"></iconify-icon>
                    </a>
                    <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdLabDelete('.$row->id.')"></iconify-icon>
                    </a>-->';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getIpdLabData(Request $request){
    $lab = LabInvestigation::find($request->id);
    if (!$lab) {
        return response()->json(['success' => false, 'message' => 'Lab record not found'], 404);
    }

    $patient = Patient::find($lab->patient_id);
    $testType = TestType::find($lab->test_type_id);
    $testName = TestName::find($lab->test_name_id);

    // Get all reports and build full URLs
    $testReports = LabReport::where('lab_id', $lab->id)->get()->map(function ($report) {
        return [
            'test_parameter' => $report->test_parameter ?? '-',
            'test_value' => $report->test_value ?? '-',
            'test_reference' => $report->test_reference ?? '-',
            'report_file_url' => asset('backend/uploads/lab_reports/' . $report->file_path),
        ];
    });

    return response()->json([
        'success' => true,
        'data' => [
            'labData' => $lab,
            'patientData' => $patient,
            'testType' => $testType,
            'testName' => $testName,
        ],
        'testReport' => $testReports,
    ]);
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
            'amount' => $request->amount,
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
            'type' => 'required',
            'name' => 'required',
            'qty' => 'nullable',
            'amount' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $payment_bills = new PaymentBill();
        $payment_bills->type = "IPD";
        $payment_bills->patient_id = $request->patientId;
        $payment_bills->admit_id = $admit_id;
        $payment_bills->amount_for = $request->type;
        $payment_bills->title = $request->name;
        $payment_bills->qty = $request->qty;
        $payment_bills->amount = $request->amount;
        if($payment_bills->save()){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->patientId;
            $timelines->admit_id = $admit_id;
            $timelines->title = "Charges";
            $timelines->desc = "Charges added for ".$request->type." ₹".$request->amount;
            $timelines->created_by = Auth::id();
            $timelines->save();
            return response()->json(['success'=>'Charge added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Charge not added']);
        }
    }
    // public function viewIpdCharge(Request $request){
    //     if($request->ajax()){
    //         $admit_id = Patient::where('id',$request->patient_id)->value('admit_id');
    //         $ipdCharges = PaymentBill::where('patient_id',$request->patient_id)->where('admit_id',$admit_id)->get();
    //         return DataTables::of($ipdCharges)
    //         ->addColumn('created_at',function($row){
    //             return $row->created_at
    //             // ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
    //             ->format('d-m-Y');      // Format as human-readable
    //         })
    //         ->addColumn('title',function($row){
    //             return $row->amount_for;
    //         })
    //         ->addColumn('desc',function($row){
    //             return $row->title;
    //         })
    //         ->addColumn('qty',function($row){
    //             return $row->qty;
    //         })
    //         ->addColumn('amount',function($row){
    //             return $row->amount;
    //         })
    //         ->addColumn('action',function($row){
    //             $visibility = $row->amount <= 0 ? 'd-none' : '';
    //             return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center ' .$visibility.'">
    //                   <iconify-icon icon="lucide:edit" onclick="ipdChargeEdit('.$row->id.')"></iconify-icon>
    //                 </a>
    //                 <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
    //                   <iconify-icon icon="mingcute:delete-2-line" onclick="ipdChargeDelete('.$row->id.')"></iconify-icon>
    //                 </a>-->';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    //     }
    // }
        public function viewIpdCharge(Request $request){
        if($request->ajax()){
            $admit_id = Patient::where('id',$request->patient_id)->value('admit_id');
            $previous_payment_bill = PaymentBill::where('patient_id', $request->patient_id)->where('admit_id',$admit_id)->where('amount_for', 'Bed Charge')->latest('id')->first();
            $occupied_days = 0;
            $pre_bed_amount = 0;
            // if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
            //     $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
            //     $admitTime = new DateTime($previous_payment_bill->created_at);
            //     $dischargeTime = new DateTime();
            //     $checkIn  = Carbon::parse($admitTime);   // e.g. 2025-09-01 13:45:18
            //     $checkOut = Carbon::parse($dischargeTime); // e.g. 2025-09-03 14:45:18
            //     $cutoffHour = 14;
            //     $days = 0;
            //     // Case 1: If check-in before 2 PM, count first day
            //     if ($checkIn->hour < $cutoffHour) {
            //         $days++;
            //     }
            //     // Case 2: Count full 24-hour blocks between check-in and check-out
            //     $days += $checkIn->diffInDays($checkOut);
            //      if ($days > 0 && $days < 1) {
            //             $days = 1;
            //         }
            
            //     // Case 3: If checkout is after 2 PM, add an extra day
            //     if ($checkOut->hour >= $cutoffHour) {
            //         $days++;
            //     }
            //     $occupied_days = (int)$days;
            //     $pre_bed_amount = $bed_amount * (int)$days;
            // }
            
            
            
            
            
             if ($previous_payment_bill && ($previous_payment_bill->amount == 0 || $previous_payment_bill->amount === NULL)) {
                $admitTime = Carbon::parse($previous_payment_bill->created_at);
                $dischargeTime = Carbon::now(); // Or use actual discharge time if available
                $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first();
                $cutoffHour = 14;
                $days = 0;
                // Case 1: If admitted before 2 PM, count the admission day
                if ($admitTime->hour < $cutoffHour) {
                $days++;
                }
                 // Start from first 2 PM after admission
                $current = $admitTime->copy()->setTime($cutoffHour, 0);
                 // Loop through each 2 PM until discharge
                while ($current <= $dischargeTime) {
                    $days++;
                    $current->addDay(); // move to next day's 2 PM
                }
                // Ensure at least 1 day
                if ($days < 1) {
                    $days = 1;
                }
                $occupied_days = $days;
                $pre_bed_amount = $bed_amount * $occupied_days;
            }
            
            
            
            
            
            $ipdCharges = PaymentBill::where('patient_id',$request->patient_id)->where('admit_id',$admit_id)->get();
            return DataTables::of($ipdCharges)
            ->addColumn('created_at',function($row){
                return $row->created_at
                // ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y');      // Format as human-readable
            })
            ->addColumn('title',function($row){
                return $row->amount_for;
            })
            ->addColumn('desc',function($row){
                return $row->title;
            })
            ->addColumn('qty', function ($row) use ($occupied_days) {
                return $row->amount == 0 ? $occupied_days : $row->qty;
            })
            ->addColumn('amount', function ($row) use ($pre_bed_amount) {
                return $row->amount == 0 ? $pre_bed_amount : $row->amount;
            })
            ->addColumn('action',function($row){
                $visibility = $row->amount <= 0 ? 'd-none' : '';
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center ' .$visibility.'">
                      <iconify-icon icon="lucide:edit" onclick="ipdChargeEdit('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center '.$visibility.'">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdChargeDelete('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getIpdChargeData(Request $request){
        $getData = PaymentBill::where('id',$request->id)->get();
        return response()->json(['success'=>'Charge data fetched','data'=>$getData],200);
    }
    public function ipdChargeDataUpdate(Request $request){
        $charge_data = PaymentBill::where('id',$request->id)->get(['patient_id','admit_id','amount']);
        $update = PaymentBill::where('id',$request->id)->update([
            'amount_for' => $request->type,
            'title' => $request->name,
            'qty' => $request->qty,
            'amount' => $request->amount
        ]);
        if($update){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $charge_data[0]->patient_id;
            $timelines->admit_id = $charge_data[0]->admit_id;
            $timelines->title = "Charges";
            $timelines->desc =  $request->type." Charges updated from ₹".$charge_data[0]->amount." to ₹".$request->amount;
            $timelines->created_by = Auth::id();
            $timelines->save();
            return response()->json(['success'=>'Charge updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Charge data not updated']);
        }
    }
    public function ipdChargeDataDelete(Request $request){
        $getData = PaymentBill::where('id',$request->id)->get(['amount_for','patient_id','admit_id','amount','title']);
        $delete = PaymentBill::where('id',$request->id)->delete();
        if($delete){
            $timelines = new Timeline();
            $timelines->type = 'IPD';
            $timelines->patient_id = $getData[0]->patient_id;
            $timelines->admit_id = $getData[0]->admit_id;
            $timelines->title = "Charges";
            $timelines->desc = $getData[0]->amount_for." ₹".$getData[0]->amount." for '".$getData[0]->title."' has been removed";
            $timelines->created_by = Auth::id();
            $timelines->save();
        }
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
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $ipdVItals = new Vital();
        $ipdVItals->type = "IPD";
        $ipdVItals->patient_id = $request->patientId;
        $ipdVItals->admit_id = $admit_id;
        $ipdVItals->name = $request->name;
        $ipdVItals->value = $request->value;
        $ipdVItals->date = $request->date;
        if($ipdVItals->save()){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->patientId;
            $timelines->admit_id = $admit_id;
            $timelines->title = "Vital";
            $timelines->desc = "Vital added of patient";
            $timelines->created_by = Auth::id();
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
            ->addColumn('admit_id',function($row){
                return 'MHAI'.$row->admit_id;
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
                    <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdVitalDelete('.$row->id.')"></iconify-icon>
                    </a>-->';
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
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $ipdNurse = new NurseNote();
        $ipdNurse->type = "IPD";
        $ipdNurse->patient_id = $request->patientId;
        $ipdNurse->admit_id = $admit_id;
        $ipdNurse->nurse_id = $request->nurseId;
        $ipdNurse->note = $request->note;
        $ipdNurse->comment = $request->comment;
        if($ipdNurse->save()){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->patientId;
            $timelines->admit_id = $admit_id;
            $timelines->title = "Nurse Note";
            $timelines->desc = "Nurse Note added of patient";
            $timelines->created_by = Auth::id();
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
                return $row->created_at
                ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y h:i A');      // Format as human-readable
            })
            ->addColumn('admit_id',function($row){
                return 'MHAI'.$row->admit_id;
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
                    <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="ipdNurseNoteDelete('.$row->id.')"></iconify-icon>
                    </a>-->';
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
     public function ipdAdvanceSubmit(Request $request){
         $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'pmode' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $optoutAdvance = new PaymentReceived();
        $optoutAdvance->patient_id = $request->patientId;
        $optoutAdvance->admit_id = $admit_id;
        $optoutAdvance->type = "IPD";
        $optoutAdvance->amount_for = "Advance";
        $optoutAdvance->amount = $request->amount;
        $optoutAdvance->payment_mode = $request->pmode;
        if($optoutAdvance->save()){
            $timelines = new Timeline();
            $timelines->type = "IPD";
            $timelines->patient_id = $request->patientId;
            $timelines->admit_id = $admit_id;
            $timelines->title = "Advance";
            $timelines->desc = "Advance Payment amount rs.".$request->amount." added";
            $timelines->created_by = Auth::id();
            $timelines->save();
            return response()->json(['success'=>'Advance added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Advance not added']);
        }
    }
    public function viewIpdAdvance(Request $request){
        if($request->ajax()){
            $admit_id = Patient::where('id',$request->patient_id)->value('admit_id');
            $advance = PaymentReceived::where('patient_id', $request->patient_id)->where('admit_id',$admit_id)->where(function($query) {
                $query->where('amount_for', 'Advance')->orWhere('amount_for', 'Discharge'); })->get();
            return DataTables::of($advance)
            ->addColumn('created_at',function($row){
                return $row->created_at
                ->setTimezone('Asia/Kolkata') // Convert to Kolkata timezone
                ->format('d-m-Y h:i A');      // Format as human-readable
            })
            ->addColumn('amount_type',function($row){
                return $row->amount_for;
            })
            ->addColumn('amount',function($row){
                return $row->amount;
            })
            ->addColumn('pmode',function($row){
                return $row->payment_mode;
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center      justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="ipdAdvanceEdit('.$row->id.')"></iconify-icon>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function getIpdAdvanceData(Request $request){
        $getData = PaymentReceived::where('id',$request->id)->get();
        return response()->json(['success'=>'Advance data fetched','data'=>$getData],200);
    }
    public function ipdAdvanceDataUpdate(Request $request){
        $update = PaymentReceived::where('id',$request->id)->update([
            'amount' => $request->amount,
            'payment_mode' => $request->pmode
        ]);
        if($update){
            return response()->json(['success'=>'Advance payment updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Advance payment data not updated']);
        }
    }
    public function labReportIpdSubmit(Request $request){
        $lab_file = $request->file('lab_pdf');
        $labreports = new LabReport();
        $labreports->patient_id = $request->patient_id;
        $labreports->lab_id = $request->lab_id;
        $labreports->title = $request->title;
        
        if ($lab_file) {
            // Define your file path and name
            $imageName =  $request->patient_id.'.'.$request->lab_id.'.'.time().'.'.$lab_file->getClientOriginalExtension();
            $destinationPath = public_path('/backend/uploads/lab_reports');
            
            // Move the file to the destination path
            $lab_file->move($destinationPath, $imageName);
            
            // Save the image path in your database
            $labreports->file_path = $imageName;
        }
        
        if ($labreports->save()) {
            return response()->json(['success' => 'Lab report added successfully'], 200);
        } else {
            return response()->json(['error_success' => 'Lab report not added'], 400);
        }
    }
    public function ipdFindingSubmit(Request $request){
        $update = Patient::where('id',$request->id)->update([
            'description' => $request->desc
        ]);
        if($update){
            return response()->json(['success'=>'Findings updated successufuly'],200);
        }else{
            return response()->json(['error_success'=>'Findings data updated']);
        }
    }
}
