<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\BedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BedtypeController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.bedtype');
    }
    public function viewBedTypes(Request $request){
        if($request->ajax()){
            $bedtype = BedType::get();
            return DataTables::of($bedtype)
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
                  <iconify-icon icon="lucide:edit" onclick="bedTypeEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="bedTypeDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addBedType(Request $request){
        $check_bedtype = BedType::where('name',$request->bedtype)->exists();
        if($check_bedtype == false){
            $validator = Validator::make($request->all(),[
                'bedtype' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $bedtypes = new BedType();
            $bedtypes->name = $request->bedtype;
            if($bedtypes->save()){
                return response()->json(['success'=>'Bed Type added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Bed Type not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Bed Type already found'],200);
        }
    }

    public function getBedTypeData(Request $request){
        $getData = BedType::where('id',$request->id)->get();
        return response()->json(['success'=>'Bed Type data fetched successfully','data'=>$getData],200);
    }
    public function updateBedTypeData(Request $request){
        $check_bedtype = BedType::where('name',$request->bedtype)->exists();
        if($check_bedtype == false){
            BedType::where('id',$request->id)->update([
                'name' => $request->bedtype
            ]);
            return response()->json(['success' => 'Bed Type updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Bed Type already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $bedtype_status = BedType::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($bedtype_status[0]->status == 1){
            $new_status = 0;
        }
        BedType::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Bed Type Status Updated Successfully'],200);
    }
    public function deleteBedTypeData(Request $request){
        BedType::where('id',$request->id)->delete();
        return response()->json(['success' => 'Bed Type Deleted Successfully'],200);
    }
}
