<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\MedicineGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MedicinegroupController extends Controller
{
  public function index()
    {
        return view('backend.admin.modules.master.medicine-group');
    }
    public function viewMedicineGroup(Request $request)
    {
         if($request->ajax()){
            $usertype = MedicineGroup::get();
            return DataTables::of($usertype)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="medicineGroupEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="medicineGroupDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addMedicineGroup(Request $request){
        $check_medGroyp = MedicineGroup::where('name',$request->group)->exists();
        if($check_medGroyp == false){
            $validator = Validator::make($request->all(),[
                'group' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $medicinegroup = new MedicineGroup();
            $medicinegroup->name = $request->group;
            if($medicinegroup->save()){
                return response()->json(['success'=>'Medicine Group added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Medicine Group not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Medicine Group already found'],200);
        }
    }
    public function getMedicineGroupData(Request $request){
        $getData = MedicineGroup::where('id',$request->id)->get();
        return response()->json(['success'=>'Medicine Group fetched successfully','data'=>$getData],200);
    }
    public function updateMedicineGroupData(Request $request){
        $check_medGroyp = MedicineGroup::where('name',$request->group)->exists();
        if($check_medGroyp == false){
            MedicineGroup::where('id',$request->id)->update([
                'name' => $request->group
            ]);
            return response()->json(['success' => 'Medicine Group updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Medicine Group already found'],200);
        }    
    }
    public function statusUpdate(Request $request){
        $groupstatus = MedicineGroup::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($groupstatus[0]->status == 1){
            $new_status = 0;
        }
        MedicineGroup::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Medicine Group Status Updated Successfully'],200);
    }
    public function deleteMedicineGroup(Request $request){
        MedicineGroup::where('id',$request->id)->delete();
        return response()->json(['success' => 'Medicine Group Deleted Successfully'],200);
    }
}
