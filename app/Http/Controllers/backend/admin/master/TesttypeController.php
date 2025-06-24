<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TesttypeController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.testtype');
    }
    public function viewTestTypes(Request $request){
        if($request->ajax()){
            $testtype = TestType::get();
            return DataTables::of($testtype)
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
                  <iconify-icon icon="lucide:edit" onclick="testTypeEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="testTypeDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addTestType(Request $request){
        $check_testtype = TestType::where('name',$request->testtype)->exists();
        if($check_testtype == false){
            $validator = Validator::make($request->all(),[
                'testtype' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $testtype = new TestType();
            $testtype->name = $request->testtype;
            if($testtype->save()){
                return response()->json(['success'=>'Test Type added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Test Type not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Test Type already found'],200);
        }
    }

    public function getTestTypeData(Request $request){
        $getData = TestType::where('id',$request->id)->get();
        return response()->json(['success'=>'Test Type data fetched successfully','data'=>$getData],200);
    }
    public function updateTestTypeData(Request $request){
        $check_testtype = TestType::where('name',$request->testtype)->exists();
        if($check_testtype == false){
            testType::where('id',$request->id)->update([
                'name' => $request->testtype
            ]);
            return response()->json(['success' => 'Test Type updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Test Type already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $testtype_status = testType::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($testtype_status[0]->status == 1){
            $new_status = 0;
        }
        testType::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'test Type Status Updated Successfully'],200);
    }
    public function deleteTestTypeData(Request $request){
        testType::where('id',$request->id)->delete();
        return response()->json(['success' => 'Test Type Deleted Successfully'],200);
    }
}
