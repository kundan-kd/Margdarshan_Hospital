<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BloodtypeController extends Controller
{
    public function index()
    {
        return view('backend.admin.modules.master.blood-type');
    }
    public function viewBloodType(Request $request)
    {
         if($request->ajax()){
            $bloodTypeData = BloodType::get();
            return DataTables::of($bloodTypeData)
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
                  <iconify-icon icon="lucide:edit" onclick="bloodTypeEdit('.$row->id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="bloodTypeDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addBloodType(Request $request){
        $check_bloodtype = BloodType::where('name',$request->bloodType)->exists();
        if($check_bloodtype == false){
            $validator = Validator::make($request->all(),[
                'bloodType' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $bloodTypes = new BloodType();
            $bloodTypes->name = $request->bloodType;
            if($bloodTypes->save()){
                return response()->json(['success'=>'blood Type added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Blood Type Category not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Blood Type already found'],200);
        }
    }
    public function getBloodTypeData(Request $request){
        $getData = BloodType::where('id',$request->id)->get();
        return response()->json(['success'=>'Blood Type data fetched successfully','data'=>$getData],200);
    }
    public function updateBloodTypeData(Request $request){
        $check_bloodtype = BloodType::where('name',$request->bloodType)->exists();
        if($check_bloodtype == false){
            BloodType::where('id',$request->id)->update([
                'name' => $request->bloodType
            ]);
            return response()->json(['success' => 'Blood Type updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Blood Type already found'],200);
        }    
    }
    public function statusUpdate(Request $request){
        $bloodTypestatus = BloodType::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($bloodTypestatus[0]->status == 1){
            $new_status = 0;
        }
        BloodType::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Blood Type Status Updated Successfully'],200);
    }
    public function deleteBloodType(Request $request){
        BloodType::where('id',$request->id)->delete();
        return response()->json(['success' => 'Blood Type Deleted Successfully'],200);
    }
}
