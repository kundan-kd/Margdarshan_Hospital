<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function index(){
        return view('backend.admin.modules.master.user');
    }
    public function viewUsers(Request $request){
          if($request->ajax()){
            $users = User::get();
            return datatables()::of($users)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('mobile',function($row){
                return $row->mobile;
            })
            ->addColumn('email',function($row){
                return $row->email;
            })
            ->addColumn('doj',function($row){
                return $row->doj;
            })
            ->addColumn('department',function($row){
                return $row->department;
            })
            ->addColumn('usertype',function($row){
                return $row->usertype;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="userEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="userDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addUser(Request $request){
        //  $validator = Validator::make($request->all(),[
        //     'bedNumber' => 'required',
        //     'bedGroup' => 'required',
        //     'bedType' => 'required',
        //     'bedFloor' => 'required'
        // ]);
        // if($validator->fails()){
        //     return response()->json(['error_validation'=> $validator->errors()->all(),],422);
        // }
        // $bed = new User();
        // $bed->bed_no = $request->bedNumber;
        // $bed->bed_group_id = $request->bedGroup;
        // $bed->bed_type_id = $request->bedType;
        // $bed->floor = $request->bedFloor;
        // if($bed->save()){
        //     return response()->json(['success'=>'bed added successfully'],201);
        // }else{
        //     return response()->json(['error_success'=>'bed not added'],500);

        // }
    }
}
