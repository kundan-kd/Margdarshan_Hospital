<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompositionController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.composition');
    }
    public function viewCompositions(Request $request){
        if($request->ajax()){
            $composition = Composition::get();
            return DataTables::of($composition)
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
                  <iconify-icon icon="lucide:edit" onclick="compositionEdit('.$row->id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="compositionDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addComposition(Request $request){
        $check_composition = Composition::where('name',$request->composition)->exists();
        if($check_composition == false){
            $validator = Validator::make($request->all(),[
                'composition' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $composition = new composition();
            $composition->name = $request->composition;
            if($composition->save()){
                return response()->json(['success'=>'composition added successfully'],201);
            }else{
                return response()->json(['error_success'=>'composition not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Composition already found'],200);
        }
    }

    public function getCompositionData(Request $request){
        $getData = Composition::where('id',$request->id)->get();
        return response()->json(['success'=>'Composition data fetched successfully','data'=>$getData],200);
    }
    public function updateCompositionData(Request $request){
        $check_composition = Composition::where('name',$request->composition)
        ->where('id', '!=', $request->id) // Exclude current record
        ->exists();
        if($check_composition == false){
            Composition::where('id',$request->id)->update([
                'name' => $request->composition
            ]);
            return response()->json(['success' => 'Composition updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Composition already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $composition_status = Composition::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($composition_status[0]->status == 1){
            $new_status = 0;
        }
        Composition::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Composition Status Updated Successfully'],200);
    }
    public function deleteCompositionData(Request $request){
        Composition::where('id',$request->id)->delete();
        return response()->json(['success' => 'Composition Deleted Successfully'],200);
    }
}
