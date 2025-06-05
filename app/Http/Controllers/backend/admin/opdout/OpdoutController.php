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
          
            ->addColumn('patient_name', function($row) {
                return $row->patient_name;
            })
            ->addColumn('token', function($row) {
                return $row->token;
            })
            ->addColumn('department_id', function($row) {
                return $row->patient_data->department_id;
            })
            ->make(true);
    }
}
   
}
