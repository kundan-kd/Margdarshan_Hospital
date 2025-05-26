<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('backend.admin.modules.master.unit');
    }
    public function viewUnit(Request $request)
    {
         if($request->ajax()){
            $unitData = Unit::get();
            return DataTables::of($unitData)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('unit',function($row){
                return $row->unit;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="unitEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="unitDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addUnit(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(),[
            'unitname' => 'required',
            'unit' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=> $validator->errors()->all(),],422);
        }
        $units = new Unit();
        $units->name = $request->unitname;
        $units->unit = $request->unit;
        if($units->save()){
            return response()->json(['success'=>'Unit added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Unit Category not added'],500);

        }
    }
    public function getUnitData(Request $request){
        $getData = Unit::where('id',$request->id)->get();
        return response()->json(['success'=>'Unit data fetched successfully','data'=>$getData],200);
    }
    public function updateUnitData(Request $request){
        // dd($request->all());
        Unit::where('id',$request->id)->update([
            'name' => $request->unitname,
            'unit' => $request->unit
        ]);
       return response()->json(['success' => 'Unit updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $unitstatus = Unit::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($unitstatus[0]->status == 1){
            $new_status = 0;
        }
        Unit::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Unit Status Updated Successfully'],200);
    }
    public function deleteUnit(Request $request){
        Unit::where('id',$request->id)->delete();
        return response()->json(['success' => 'Unit Deleted Successfully'],200);
    }
}
