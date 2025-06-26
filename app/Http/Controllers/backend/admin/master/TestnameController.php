<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\TestName;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TestnameController extends Controller
{
     public function index(){
        $testtypes = TestType::where('status',1)->get();
        return view('backend.admin.modules.master.testname',compact('testtypes'));
    }
    public function viewTestNames(Request $request){
        if($request->ajax()){
            $testtype = TestName::get();
            return DataTables::of($testtype)
            ->addColumn('testname',function($row){
                return $row->name;
            })
            ->addColumn('sname',function($row){
                return $row->s_name;
            })
            ->addColumn('testtype',function($row){
                return $row->test_type_id;
            })
            ->addColumn('amount',function($row){
                return $row->amount;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="testNameEdit('.$row->id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="testNameDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addTestName(Request $request){
        $check_testname = TestName::where('test_type_id',$request->testType_id)->where('name',$request->testName)->exists();
        if($check_testname == false){
            $validator = Validator::make($request->all(),[
                'testType_id' => 'required',
                'testName' => 'required',
                'testShortName' => 'nullable',
                'testAmount' => 'nullable'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $testtype = new TestName();
            $testtype->test_type_id = $request->testType_id;
            $testtype->name = $request->testName;
            $testtype->s_name = $request->testShortName;
            $testtype->amount = $request->testAmount;
            if($testtype->save()){
                return response()->json(['success'=>'Test Name added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Test Name not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Test Name configuration already found'],200);
        }
    }
    public function getTestNameData(Request $request){
        $getData = TestName::where('id',$request->id)->get();
        return response()->json(['success'=>'Test Type data fetched successfully','data'=>$getData],200);
    }
    public function updateTestNameData(Request $request){
        // $check_testname = TestName::where('test_type_id',$request->testType_id)->where('name',$request->testName)->exists();
       // if($check_testname == false){
            TestName::where('id',$request->id)->update([
                'test_type_id' => $request->testType_id,
                'name' => $request->testName,
                's_name' => $request->testShortName,
                'amount' => $request->testAmount
            ]);
            return response()->json(['success' => 'Test Name updated successfully'],200);
        // }else{
        //     return response()->json(['already_found'=>'This Test Name configuration already found'],200);
        // }
    }
    public function statusUpdate(Request $request){
        $testtype_status = TestName::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($testtype_status[0]->status == 1){
            $new_status = 0;
        }
        TestName::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'test Type Status Updated Successfully'],200);
    }
    public function deleteTestNameData(Request $request){
        TestName::where('id',$request->id)->delete();
        return response()->json(['success' => 'Test Name Deleted Successfully'],200);
    }
}
