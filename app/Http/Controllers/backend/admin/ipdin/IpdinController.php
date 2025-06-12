<?php

namespace App\Http\Controllers\backend\admin\ipdin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IpdinController extends Controller
{
     public function index(){
        // $users = User::where('usertype_id',2)->get(['id','firstname','lastname','room_number']);
        return view('backend.admin.modules.ipdin.ipd-in');
    }
    function ipdInPatientAdd(){
            return view('backend.admin.modules.ipdin.ipd-in-PatientAdd');
    }
    function ipdInDetails(){
      // $patients = Patient::where('id',$id)->get();
      // $appointments = Appointment::where('patient_id',$patients[0]->id)->get();
      // $medicineCategory = MedicineCategory::where('status',1)->get();
      // $doctorData = User::where('status',1)->get(['id','firstname','lastname','department_id']);
            return view('backend.admin.modules.ipdin.ipd-in-details');
    }
}
