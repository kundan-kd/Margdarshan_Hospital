<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\PaymentBill;
use App\Models\PaymentMode;
use App\Models\PaymentReceived;
use App\Models\RoomNumber;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
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
        return view('backend.admin.modules.appointment.appointment',compact('departments','paymentmodes','patients','doctors'));
    }
    public function viewAppointments(Request $request){
     if($request->ajax()){
        $appointment = Appointment::get();
        return DataTables::of($appointment)
        ->addColumn('patient_id',function($row){
            return $row->patient_data->patient_id;
            // return '<a target="_blank" class="text-primary cursor-pointer" onclick="opdPatientUsingId('.$row->patient_id.')">'.$row->patient_data->patient_id.'</a>';
        })
        ->addColumn('patient_name',function($row){
            return $row->patient_name;
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
        ->addColumn('doctor',function($row){
            return "Dr. ".$row->user_data->name;
        })
        
        ->addColumn('fee',function($row){
            return $row->fee;
        })
        ->addColumn('paid_status',function($row){
            return $row->paid_status === 'Paid'? '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white">Paid</span>': '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white" >Unpaid</span>';  
        })
        ->addColumn('status',function($row){
            return $row->status === 'Visited'? '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white">Visited</span>': '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white" >Pending</span>'; 
        })
       ->addColumn('action', function($row) {
            $check_invoice = $row->paid_status == 'UnPaid' ? 'd-none' : '';
            $check_payment = $row->paid_status == 'Paid' ? 'd-none' : '';
            $check_visit = $row->status == 'Visited' ? 'd-none' : '';
            return '
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center '.$check_invoice.'">
                    <iconify-icon icon="mdi:file-download-outline" onclick="printAppointmentBill(' . $row->id . ')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_payment.'">
                    <iconify-icon icon="lucide:edit" onclick="appointmentEdit(' . $row->id . ')" ></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center '.$check_visit.'">
                    <iconify-icon icon="lucide:calendar" onclick="visitEdit(' . $row->id . ')" ></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line" onclick="deleteReason(' . $row->id . ')"></iconify-icon>
                </a>';
        })
        ->rawColumns(['patient_id','paid_status','status','action'])
        ->make(true);
     }
    }

    public function addNewPatient(Request $request){
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
            return response()->json(['success'=>'New Patient added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Patient not added'],500);
        }
    }
    public function searchPatient(Request $request){
        $getData = Patient::where('name','LIKE',"%{$request->name}%")
                            ->orWhere('patient_id', 'LIKE', "%{$request->name}%")
                            ->orWhere('mobile','LIKE',"%{$request->name}%")
                            ->get(['id','patient_id','name']);
        return response()->json(['success'=>'Patient data fetched successfully','data'=>$getData],200);
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
        $validator = Validator::make($request->all(),[
            'patientID' => 'nullable',
            'name' => 'required',
            'departmentID' => 'required',
            'doctorID' => 'required',
            'date' => 'required',
            // 'pmode' => 'required',
            'rnum' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $month = date('m'); // Gets the current month (e.g., "05")
        $year = date('y'); // Gets the current year (e.g., "25")
        $appointment = new Appointment();
        $appointment->patient_id = 'OPD';
        $appointment->patient_id = $request->patientID;
        $appointment->patient_name = $request->name;
        $appointment->department_id = $request->departmentID;
        $appointment->doctor_id = $request->doctorID;
        $appointment->appointment_date = $request->date;
        // $appointment->payment_mode = $request->pmode;
        $appointment->room_number = $request->rnum;
        $appointment->fee = $request->fee;
        $appointment->paid_status = "UnPaid";
        if($appointment->save()){
            $appointment->token = "MHAP". $month.$year.$appointment->id;
            $appointment->save();
            $payment_bills = new PaymentBill();
            $payment_bills->type = "OPD";
            $payment_bills->type_id = $appointment->id;
            $payment_bills->patient_id = $request->patientID;
            $payment_bills->amount_for = 'OPD Consultant';
            $payment_bills->title = 'OPD Appointment Fee';
            $payment_bills->amount = $request->fee;
            $payment_bills->save();
            return response()->json(['success'=>'Appointment booked successfully'],200);
        }else{
            return response()->json(['error_success'=>'Appointment not booked'],500);
        }
    }
    public function getAppointmentData(Request $request){
        $getData = Appointment::where('id',$request->id)->get();
        return response()->json(['success'=>'Appointment details fetched successfully','data'=>$getData],200);
    }
    public function updateAppointmentData(Request $request){
        $appointment_data = Appointment::where('id',$request->id)->get(['id','patient_id','fee']);
        $update = Appointment::where('id',$request->id)->update([
            'paid_amount' => $request->pay_amount,
            'payment_mode' => $request->payment_mode,
            'paid_status' => "Paid"
        ]);
        if($update){
            if($request->pay_amount > 0){
                $payment_received = new PaymentReceived();
                $payment_received->patient_id = $appointment_data[0]->patient_id;
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
        $appointment_data = Appointment::where('id',$request->id)->get(['id','patient_id','fee','appointment_date','doctor_id']);
        $update = Appointment::where('id',$request->id)->update([
            'status' => "Visited"
        ]);
        if($update){
            $visits = new Visit();
            $visits->type = "OPD";
            $visits->patient_id = $appointment_data[0]->patient_id;
            $visits->appointment_id = $appointment_data[0]->id;
            $visits->consult_doctor = $appointment_data[0]->doctor_id;
            $visits->appointment_date = $appointment_data[0]->appointment_date;
            $visits->visited_date = $request->visit_date;
            $visits->paid_amount = $appointment_data[0]->fee;
            $visits->save();
            return response()->json(['success' => 'Visits updated successfully'],200);
        }else{
            return response()->json(['error_success' => 'Visits not updated']);
        }
    }
    public function deleteAppointmentData(Request $request){
        $update = Appointment::where('id',$request->id)->update([
            'reason_for_delete' => $request->reason,
            'status' => 'Deleted'
        ]);
        if($update){
            Appointment::where('id',$request->id)->delete();
            return response()->json(['success' => 'Appointment cancelled successfully'],200);
        }else{
            return response()->json(['error_success' => 'Appointment not cancelled']);

        }
    }
}

