<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\NarationList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class NarationlistController extends Controller
{
   public function index(){
        return view('backend.admin.modules.master.naration-list');
    }
    public function viewNarationLists(Request $request){
        if($request->ajax()){
            $naraitonlist = NarationList::get();
            return DataTables::of($naraitonlist)
            ->addColumn('name',function($row){
                return $row->naration;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="narationListEdit('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addNarationList(Request $request){
            $validator = Validator::make($request->all(),[
                'narationList' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),]);
            }
            $narationlist = new NarationList();
            $narationlist->type = 'Lead';
            $narationlist->naration = $request->narationList;
            if($narationlist->save()){
                return response()->json(['success'=>'Narration added successfully'],200);
            }else{
                return response()->json(['error_success'=>'Narration not added'],500);
            }
    }

    public function getNarationListData(Request $request){
        $getData = NarationList::where('id',$request->id)->get();
        return response()->json(['success'=>'Naration List data fetched successfully','data'=>$getData],200);
    }
    public function updateNarationListData(Request $request){
        NarationList::where('id',$request->id)->update([
            'naration' => $request->narationList
        ]);
        return response()->json(['success' => 'Narration updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $usertype_status = NarationList::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($usertype_status[0]->status == 1){
            $new_status = 0;
        }
        NarationList::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'User Type Status Updated Successfully'],200);
    }
}
