<?php

namespace App\Http\Controllers\backend\admin\appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\PaymentMode;
use App\Models\RoomNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        ->addColumn('token',function($row){
            return $row->token;
        })
        ->addColumn('fee',function($row){
            return $row->fee;
        })
        ->addColumn('status',function($row){
            return $row->status;
        })
        ->addColumn('action',function($row){
            return '<!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a> -->
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="lucide:edit" onclick="appointmentEdit('.$row->id.');getDoctorAdded('.$row->id.')"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                      <iconify-icon icon="mingcute:delete-2-line" onclick="appointmenttDelete('.$row->id.')"></iconify-icon>
                    </a>';
        })
        ->rawColumns(['action'])
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
            return response()->json(['success'=>'New Patient added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Patient not added'],500);
        }
    }
    public function searchPatient(Request $request){
        $getData = Patient::where('name','LIKE',"%{$request->name}%")
                            ->orWhere('patient_id', 'LIKE', "%{$request->name}%")
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
            'pmode' => 'required',
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
        $appointment->payment_mode = $request->pmode;
        $appointment->room_number = $request->rnum;
        $appointment->fee = $request->fee;
        $appointment->status = "Paid";
        if($appointment->save()){
            $appointment->token = "MHAP". $month.$year.$appointment->id;
            $appointment->save();
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
        Appointment::where('id',$request->id)->update([
            'patient_name' => $request->name,
            'department_id' => $request->departmentID,
            'doctor_id' => $request->doctorID,
            'appointment_date' => $request->aDate,
            'payment_mode' => $request->pmode
        ]);
       return response()->json(['success' => 'Appointment updated successfully'],200);
    }
    public function deleteAppointmentData(Request $request){
        Appointment::where('id',$request->id)->delete();
        return response()->json(['success' => 'Appointment Deleted Successfully'],200);
    }
}

