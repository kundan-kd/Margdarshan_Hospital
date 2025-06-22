<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsertypeController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.usertype');
    }
    public function viewUserTypes(Request $request){
        if($request->ajax()){
            $usertype = UserType::get();
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
                  <iconify-icon icon="lucide:edit" onclick="userTypeEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="userTypeDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addUserType(Request $request){
        $check_usertype = UserType::where('name',$request->usertype)->exists();
        if($check_usertype == false){
            $validator = Validator::make($request->all(),[
                'usertype' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $usertype = new UserType();
            $usertype->name = $request->usertype;
            if($usertype->save()){
                return response()->json(['success'=>'Usertype added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Usertype not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This User Type already found'],200);
        }
    }

    public function getUserTypeData(Request $request){
        $getData = UserType::where('id',$request->id)->get();
        return response()->json(['success'=>'User Type data fetched successfully','data'=>$getData],200);
    }
    public function updateUserTypeData(Request $request){
        $check_usertype = UserType::where('name',$request->usertype)->exists();
        if($check_usertype == false){
            UserType::where('id',$request->id)->update([
                'name' => $request->usertype
            ]);
            return response()->json(['success' => 'User Type updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This User Type already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $usertype_status = UserType::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($usertype_status[0]->status == 1){
            $new_status = 0;
        }
        UserType::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'User Type Status Updated Successfully'],200);
    }
    public function deleteUserTypeData(Request $request){
        UserType::where('id',$request->id)->delete();
        return response()->json(['success' => 'User Type Deleted Successfully'],200);
    }
}
