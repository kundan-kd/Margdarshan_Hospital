<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\BedGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BedgroupController extends Controller
{
     public function index(){
        return view('backend.admin.modules.master.bedgroup');
    }
    public function viewbedGroups(Request $request){
        if($request->ajax()){
            $bedGroup = BedGroup::get();
            return DataTables::of($bedGroup)
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
                  <iconify-icon icon="lucide:edit" onclick="bedGroupEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="bedGroupDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addBedGroup(Request $request){
        $check_bedGroup = BedGroup::where('name',$request->bedGroup)->exists();
        if($check_bedGroup == false){
            $validator = Validator::make($request->all(),[
                'bedGroup' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $bedGroups = new BedGroup();
            $bedGroups->name = $request->bedGroup;
            if($bedGroups->save()){
                return response()->json(['success'=>'Bed Group added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Bed Group not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Bed Group already found'],200);
        }
    }

    public function getBedGroupData(Request $request){
        $getData = BedGroup::where('id',$request->id)->get();
        return response()->json(['success'=>'Bed Group data fetched successfully','data'=>$getData],200);
    }
    public function updateBedGroupData(Request $request){
        $check_bedGroup = BedGroup::where('name',$request->bedGroup)->exists();
        if($check_bedGroup == false){
            bedGroup::where('id',$request->id)->update([
                'name' => $request->bedGroup
            ]);
            return response()->json(['success' => 'Bed Group updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Bed Group already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $bedGroup_status = BedGroup::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($bedGroup_status[0]->status == 1){
            $new_status = 0;
        }
        BedGroup::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Bed Group Status Updated Successfully'],200);
    }
    public function deleteBedGroupData(Request $request){
        BedGroup::where('id',$request->id)->delete();
        return response()->json(['success' => 'Bed Group Deleted Successfully'],200);
    }
}
