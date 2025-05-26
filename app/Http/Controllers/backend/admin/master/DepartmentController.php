<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.department');
    }
    public function viewDepartments(Request $request){
        if($request->ajax()){
            $department = Department::get();
            return DataTables::of($department)
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
                  <iconify-icon icon="lucide:edit" onclick="departmentEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="departmentDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addDepartment(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(),[
            'department' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=> $validator->errors()->all(),],422);
        }
        $department = new Department();
        $department->name = $request->department;
        if($department->save()){
            return response()->json(['success'=>'Department added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Depertment not added'],500);

        }
    }

    public function getDepartmentData(Request $request){
        $getData = Department::where('id',$request->id)->get();
        return response()->json(['success'=>'Department fetched successfully','data'=>$getData],200);
    }
    public function updateDepartmentData(Request $request){
        Department::where('id',$request->id)->update([
            'name' => $request->department
        ]);
       return response()->json(['success' => 'User Type updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $department_status = Department::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($department_status[0]->status == 1){
            $new_status = 0;
        }
        Department::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Department Status Updated Successfully'],200);
    }
    public function deleteDepertmentData(Request $request){
        Department::where('id',$request->id)->delete();
        return response()->json(['success' => 'Department Deleted Successfully'],200);
    }
}
