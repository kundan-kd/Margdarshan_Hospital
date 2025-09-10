<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\AdmitList;
use App\Models\Bed;
use App\Models\LabInvestigation;
use App\Models\Lead;
use App\Models\Medication;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Patient;
use App\Models\TestName;
use App\Models\TestType;
use App\Models\Timeline;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;
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
        // Get latest patient entry per patient_id using Eloquent
        $latestPatients = Patient::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')
                    ->from('patients')
                    ->groupBy('patient_id');
        })->get();
        return DataTables::of($latestPatients)
        ->addColumn('patient_id',function($row){
            // return '<a target="_blank" class="text-primary cursor-pointer" onclick="patientHistory('.$row->id.','.$row->admit_id.')">'.$row->patient_id.'</a>';
            return $row->patient_id;
        })
        ->addColumn('name',function($row){
            return $row->name;
        })
        ->addColumn('type',function($row){
            return $row->type ?? 'NA';
        })
        ->addColumn('entry_type',function($row){
            return $row->entry_type ?? 'NA';
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
        // ->addColumn('created_at',function($row){
        //     $date = new \DateTime($row->created_at);
        //     $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        //     return $date->format('d-m-Y h:i A');
        // })
        ->addColumn('curr_status',function($row){
            return $row->current_status ?? 'NA';
        })
                ->addColumn('action', function($row){
                    return '
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                          <iconify-icon icon="lucide:edit" onclick="patientNewEdit('.$row->id.')"></iconify-icon>
                        </a>';
                })
                ->rawColumns(['patient_id','action'])
                ->make(true);
        }
    }
    public function patientHistory($id,$admit_id){
        // dd($id,$admit_id);
        $patients = Patient::where('id',$id)->get();
        $medicineCategory = MedicineCategory::where('status',1)->get();
        $doctorData = User::where('status',1)->where('usertype_id',2)->get(['id','name','department_id']);
        $nurseData = User::where('status',1)->where('usertype_id',3)->get(['id','name','department_id']);
        $visitsData = Visit::where('patient_id',$patients[0]->id)->where('admit_id',$admit_id)->get();
        $medicationData = Medication::with('medicineNameData')->where('patient_id',$patients[0]->id)->where('admit_id',$admit_id)->get();
        $testtypes = TestType::where('status',1)->get();
        $testnames = TestName::where('status',1)->get();
        $labInvestigationData = LabInvestigation::where('patient_id',$patients[0]->id)->get();
        $emergencyAvailBeds = Bed::where('bed_group_id',6)->where('current_status','vacant')->where('status',1)->get();
        $icuAvailBeds = Bed::where('bed_group_id',4)->where('current_status','vacant')->where('status',1)->get();
        $ipdAvailablelBeds = Bed::where('bed_group_id',5)->where('current_status','vacant')->where('status',1)->get();
        return view('backend.admin.modules.appointment.patient-history',compact('patients','medicineCategory','doctorData','nurseData','visitsData','medicationData','testtypes','testnames','labInvestigationData','emergencyAvailBeds','icuAvailBeds','ipdAvailablelBeds'));
    }
    public function deletePatientData(Request $request){
         Patient::where('id',$request->id)->delete();
        return response()->json(['success' => 'Patient Deleted Successfully'],200);
    }
    public function patientAddNewPatient(Request $request){
    return DB::transaction(function () use ($request) {
        $existing_patient = Patient::where('mobile', $request->mobile)->exists();
        if ($existing_patient) {
            return response()->json(['alreadyFound' => 'Patient already exists with this mobile number']);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'guardian_name' => 'required',
            'gender' => 'nullable',
            'entry_type' => 'required',
            'bloodtype' => 'nullable',
            'dob' => 'required',
            'mstatus' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'alt_mobile' => 'nullable',
            'allergy' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['error_validation' => $validator->errors()->all()], 422);
        }
        $month = date('m');
        $year = date('y');
        // $existing_patient_data = Patient::where('mobile', $request->mobile)->first();
        $patient = new Patient();
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
        if ($patient->save()) {
                // Generate new patient_id and barcode
                $patient->patient_id = "MHPT" . $month . $year . $patient->id;
                // Barcode generation logic
                $generator = new BarcodeGeneratorPNG();
                $barcode = $generator->getBarcode($patient->patient_id, $generator::TYPE_CODE_128);
    
                if ($barcode) {
                    $fileName = $patient->patient_id . '.' . time() . '.png';
                    $path = public_path('backend/uploads/barcode/' . $fileName);
                    file_put_contents($path, $barcode);
                    $patient->barcode = $fileName;
                }
            $patient->save(); // Final save after assigning patient_id and barcode
            // store timelines of patient    
            $timelines = new Timeline();
            $timelines->type = "NA";
            $timelines->patient_id = $patient->id;
            $timelines->title = "Patient Added";
            $timelines->desc = "New patient added";
            $timelines->created_by = Auth::id();
            $timelines->save();
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
            return response()->json(['success' => 'New Patient added successfully'], 201);
        }

        return response()->json(['error_success' => 'Patient not added'], 500);
    });
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
            'entry_type' => $request->entry_type,
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
