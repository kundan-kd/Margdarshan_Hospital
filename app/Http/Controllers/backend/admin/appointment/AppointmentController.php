<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\AdmitList;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PaymentBill;
use App\Models\PaymentMode;
use App\Models\PaymentReceived;
use App\Models\RoomNumber;
use App\Models\Timeline;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    public function index(){
        $departments = Department::where('status', 1) ->where('name', '!=', 'Admin')->get();
        $paymentmodes = PaymentMode::where('status',1)->get();
        $patients = Patient::where('status',1)->get();
        $doctors = User::where('status',1)->where('usertype_id',2)->get(['id','name']);
        $opd_rooms = RoomNumber::where('room_group_id',7)->get(['id','room_num']);
        return view('backend.admin.modules.appointment.appointment',compact('departments','paymentmodes','patients','doctors','opd_rooms'));
    }
    
    public function viewAppointments(Request $request){
        if($request->ajax()){
            $patient_log = PatientLog::where('type','OPD')->get();
            return DataTables::of($patient_log)
            ->addColumn('appointment_date',function($row){
                return Carbon::parse($row->appointment_date)->format('d-m-Y') ?? '';
            })
            ->addColumn('appointment_id',function($row){
                return 'MHAP0'.$row->id ?? '';
            })
            ->addColumn('patient_name',function($row){
                return $row->patient_data->name ?? '';
            })
            ->addColumn('mobile',function($row){
                return $row->patient_data->mobile ?? ''; //fetched through modal relationship
            })
            ->addColumn('gender',function($row){
                return $row->patient_data->gender ?? '';
            })
            ->addColumn('doctor',function($row){
                return "Dr. ".$row->user_data->name ?? '';
            })
            ->addColumn('fee',function($row){
                return $row->fee ?? '';
            })
            ->addColumn('payment_status',function($row){
                return $row->payment_status == 'Paid'? '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white">Paid</span>': '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white" >Unpaid</span>';  
            })
            ->addColumn('status',function($row){
                return $row->status == 'Pending' || $row->status == 'Cancelled'? '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white">'.$row->status.'</span>': '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white" >'.$row->status.'</span>'; 
            })
            ->addColumn('action', function($row) {
                $check_invoice = $row->payment_status == 'UnPaid' ? 'd-none' : '';
                $check_payment = $row->payment_status == 'Paid' ? 'd-none' : '';
                $check_visit = ($row->status == 'Visited' || $row->status == 'Moved to IPD' || $row->status == 'Cancelled') ? 'd-none' : '';
                return '
                <div class="d-flex gap-1">
                    <a href="javascript:void(0)" title="Appointment Bill" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center '.$check_invoice.'">
                        <iconify-icon icon="mdi:file-download-outline" onclick="printAppointmentBill(' . $row->id . ')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" title="Bill Payment" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_payment.'">
                        <iconify-icon icon="lucide:edit" onclick="appointmentEdit(' . $row->id . ')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" title="Visit Date" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_visit.'">
                        <iconify-icon icon="lucide:calendar" onclick="visitEdit(' . $row->id . ')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" title="Change Appointment Date" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_visit.' ">
                        <iconify-icon icon="lucide:calendar" onclick="appointmentDateEdit(' . $row->id . ')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" title="Delete" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_visit.'">
                        <iconify-icon icon="mdi:close" style="font-size:18px;" onclick="deleteReason(' . $row->id . ')"></iconify-icon>
                    </a>
                </div>';
            })
            ->rawColumns(['patient_id','payment_status','status','action'])
            ->make(true);
        }
    }
    
    public function addNewPatient(Request $request){
        $existing_patient = Patient::where('mobile', $request->mobile)->exists();
        if ($existing_patient) {
            return response()->json([
                'alreadyFound' => 'Patient already exists with this mobile number']);
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
            $timelines = new Timeline();
            $timelines->type = "OPD";
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
            return response()->json(['success' => 'New Patient added successfully'], 200);
        }
        return response()->json(['error_success' => 'Patient not added']);
    }
    
    public function searchPatient(Request $request){
        $keyword = $request->input('name');
    
        $latestDistinctIds = Patient::where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('patient_id', 'LIKE', "%{$keyword}%")
                  ->orWhere('mobile', 'LIKE', "%{$keyword}%");
        })
        ->orderByDesc('created_at')
        ->pluck('id')
        ->unique()
        ->take(1);
    
        $getData = Patient::whereIn('id', $latestDistinctIds)
                          ->orderByDesc('created_at') // Ensure latest first
                          ->get(['id','patient_id','name','current_status','type']);
    
        return response()->json([
            'success' => 'Latest distinct patient data fetched successfully',
            'data' => $getData
        ], 200);
    }
    
    public function getPatient(Request $request){
        $getData = Patient::where('id',$request->id)->get(['id','patient_id','name']);
        return response()->json(['success'=>'Patient details fetched successfully','data'=>$getData],200);
    }
    
    public function getDoctorData(Request $request){
        $getData = '';
        $roomNum = '';
        if($request->id != null || $request->id !=''){
            $getData = User::where('id',$request->id)->get(['fee','room_number']);
            $roomNum = RoomNumber::where('id',$getData[0]->room_number)->get(['id','room_num']);
        }
        return response()->json(['success'=>'Doctor details fetched successfully','data'=>$getData,'roomNum'=>$roomNum],200);
    }
    
    public function getDoctorList(Request $request){
        $doctors = User::where('status',1)->where('department_id',$request->departmentID)->where('usertype_id',2)->get(['id','name']);
        return response()->json(['success'=>'Doctor list fetched successfully','data'=>$doctors],200);
    }
    public function getDoctorAddedData(Request $request){
        // dd($request->all());
        $appointment = Appointment::where('id',$request->id)->get(['id','doctor_id']);
        $doctorData = User::where('id',$appointment[0]->doctor_id)->get(['name','fee','room_number']);
        $roomNum = RoomNumber::where('id',$doctorData[0]->room_number)->get(['room_num']);
        // dd($appointment,$doctorName);
        return response()->json(['success'=>'Doctor detail fetched successfully','data'=>$appointment,'doctorData' =>$doctorData,'roomNum'=>$roomNum],200);
    }
    
    public function appointmentBook(Request $request){
        return DB::transaction(function () use ($request) {
            $prevPatient = Patient::where('id',$request->patientID)->latest('id')->first();
            if($prevPatient->current_status == "Admitted"){
                return response()->json(['already_admitted'=>'This patient is already admitted']);
            }
            // if($prevPatient->discharge_form_generated == 0){
            //     return response()->json(['discharge_form_generate_issue'=>'Please submit previous discharge summary before adding new']);
            // }
            $validator = Validator::make($request->all(),[
                'patientID' => 'nullable',
                'name' => 'required',
                'departmentID' => 'required',
                'doctorID' => 'required',
                'date' => 'required',
                'rnum' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=>$validator->errors()->all()],200);
            }
            $patient_log = new PatientLog();
            $patient_log->patient_id = $request->patientID;
            $patient_log->type = "OPD";
            $patient_log->department_id = $request->departmentID;
            $patient_log->doctor_id = $request->doctorID;
            $patient_log->appointment_date = $request->date;
            $patient_log->room_id = $request->rnum;
            $patient_log->fee = $request->fee;
            $patient_log->status = 'Pending';
            if($patient_log->save()){
                Patient::where('id',$request->patientID)->update([
                    'appointment_status' => 1,
                    'type' => 'OPD',
                    'current_status' => 'OPD'
                ]);
                $payment_bills = new PaymentBill();
                $payment_bills->type = "OPD";
                $payment_bills->patient_id = $request->patientID;
                $payment_bills->amount_for = 'OPD Consultant';
                $payment_bills->title = 'OPD Appointment Fee';
                $payment_bills->amount = $request->fee;
                $payment_bills->save();

                $timelines = new Timeline();
                $timelines->type = "OPD";
                $timelines->patient_id = $request->patientID;
                $timelines->title = "Appointment Booked";
                $timelines->desc = "OPD appointment booked";
                $timelines->created_by = Auth::id();
                $timelines->save();
                return response()->json(['success'=>'Appointment booked successfully'],200);
            }else{
                return response()->json(['error_success'=>'Appointment not booked'],500);
            }
        });
    }
    
    public function getAppointmentData(Request $request){
        $getData = PatientLog::where('id',$request->id)->get();
        return response()->json(['success'=>'Appointment details fetched successfully','data'=>$getData],200);
    }
    
    public function updateAppointmentData(Request $request){
        $appointment_data = PatientLog::where('id',$request->id)->get(['id','patient_id','admit_id','fee']);
        $update = PatientLog::where('id',$request->id)->update([
            'paid_amount' => $request->pay_amount,
            'payment_status' => "Paid"
        ]);
        if($update){
            if($request->pay_amount > 0){
                $payment_received = new PaymentReceived();
                $payment_received->patient_id = $appointment_data[0]->patient_id;
                $payment_received->admit_id = $appointment_data[0]->admit_id;
                $payment_received->type = 'OPD';
                $payment_received->type_id = $appointment_data[0]->id;
                $payment_received->amount_for = 'OPD Consultant';
                $payment_received->title = 'OPD Appointment Fee';
                $payment_received->amount =  $appointment_data[0]->fee;
                $payment_received->payment_mode =  $request->payment_mode;
                $payment_received->save();
            }
            return response()->json(['success' => 'Appointment updated successfully'],200);
        }else{
            return response()->json(['error_success' => 'Appointment not updated']);
        }
    }
    
    public function updateVisitData(Request $request){
        return DB::transaction(function () use ($request) {
        $appointment_data = PatientLog::where('id', $request->id)->get(['id', 'patient_id','admit_id','fee', 'appointment_date', 'doctor_id']);
        if($request->visit_date != $appointment_data[0]->appointment_date){
             return response()->json(['error_visit_update' => 'Visit updates are allowed only on the appointment date']);
        }
            $appointment_statuses = PatientLog::where('patient_id', $appointment_data[0]->patient_id)->pluck('status')->toArray(); 
            // Only update Patient table if no status is 'Pending' in patient log
            if (!in_array('Pending', $appointment_statuses)) {
                Patient::where('id', $appointment_data[0]->patient_id)->update([
                    'appointment_status' => 0,
                    'current_status' => 'Visited',
                    'description' => 'Visited to OPD'
                ]);
            }
            // Update the current PatientLog status
            $update = PatientLog::where('id', $request->id)->update([
                'admit_id' => $appointment_data[0]->patient_id,
                'status' => "Visited",
                'description' => "Visited in OPD",
            ]);
            if ($update) {
                $visits = new Visit();
                $visits->type = "OPD";
                $visits->patient_id = $appointment_data[0]->patient_id;
                $visits->appointment_id = $appointment_data[0]->id;
                $visits->consult_doctor = $appointment_data[0]->doctor_id;
                $visits->appointment_date = $appointment_data[0]->appointment_date;
                $visits->visited_date = $request->visit_date;
                $visits->amount = $appointment_data[0]->fee;
                $visits->paid_amount = $appointment_data[0]->fee;
                $visits->save();
                return response()->json(['success' => 'Visits updated successfully'], 200);
            } else {
                return response()->json(['error_success' => 'Visits not updated']);
            }
        });
    }
    public function deleteAppointmentData(Request $request){
        $update = PatientLog::where('id',$request->id)->update([
            'admit_id' => '000',
            'appointment_cancel_reason' => $request->reason,
            'status' => 'Cancelled',
            'description' => 'Appointment cancelled by user ' . Auth::id()
        ]);
        if($update){
            return response()->json(['success' => 'Appointment cancelled successfully'],200);
        }else{
            return response()->json(['error_success' => 'Appointment not cancelled']);
        }
    }
    
    public function appointmentDataUpdate(Request $request){
        $update = PatientLog::where('id',$request->id)->update([
            'appointment_date' => $request->newDate
        ]);
        if($update){
             return response()->json(['success' => 'Appointment date updated successfully'],200);
        }else{
            return response()->json(['error_success' => 'Appointment date not updated']);
        }
    }
}

