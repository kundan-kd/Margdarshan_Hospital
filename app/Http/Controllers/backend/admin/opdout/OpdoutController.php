<?php

namespace App\Http\Controllers\backend\admin\opdout;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OpdoutController extends Controller
{
    public function index(){
        $users = User::where('usertype_id',4)->get(['id','firstname','lastname','room_number']);
        return view('backend.admin.modules.opdout.opd-out',compact('users'));
    }
   public function viewOpdOut(Request $request){
        if($request->ajax()){
            if($request->doctor_id != null && $request->roomNum != null){
                $appointment = Appointment::where('doctor_id',$request->doctor_id)->where('room_number',$request->roomNum)->get();
            }else if($request->doctor_id != null && $request->roomNum == null){
                $appointment = Appointment::where('doctor_id',$request->doctor_id)->get();
            }else if($request->doctor_id == null && $request->roomNum != null){
                $appointment = Appointment::where('room_number',$request->roomNum)->get();
            }else{
                 $appointment = Appointment::get();
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
            ->rawColumns(['token'])
            ->make(true);
         }
    }
    public function getPatientUsingDoctor(Request $request){
        $doctorID = $request->id;
        if($doctorID !=''){
            $getData = Appointment::where('doctor_id',$doctorID)->get();
        }else{
            $getData = Appointment::get();
        } 
        return response()->json(['success'=>'Appointment Details fetched successfully','data'=>$getData],200);
    }
    public function patientDetails(){
        return view('backend.admin.modules.opdout.opd-out-patient');
    }
}
