<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomtypeController extends Controller
{
   public function index(){
        return view('backend.admin.modules.master.roomtype');
    }
    public function viewRoomTypes(Request $request){
        if($request->ajax()){
            $roomtype = RoomType::get();
            return DataTables::of($roomtype)
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
                  <iconify-icon icon="lucide:edit" onclick="roomTypeEdit('.$row->id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="roomTypeDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addRoomType(Request $request){
        $room_check = RoomType::where('name',$request->roomtype)->exists();
        if($room_check == false){
            $validator = Validator::make($request->all(),[
                'roomtype' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $roomtype = new RoomType();
            $roomtype->name = $request->roomtype;
            if($roomtype->save()){
                return response()->json(['success'=>'Room Type added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Room Type not added'],500);
            }
        }else{
            return response()->json(['already_found' => 'Room Type already exists'],200);
        }    
    }

    public function getRoomTypeData(Request $request){
        $getData = RoomType::where('id',$request->id)->get();
        return response()->json(['success'=>'Room Type data fetched successfully','data'=>$getData],200);
    }
    public function updateRoomTypeData(Request $request){
            RoomType::where('id',$request->id)->update([
                'name' => $request->roomtype
            ]);
            return response()->json(['success' => 'Room Type updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $roomtype_status = RoomType::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($roomtype_status[0]->status == 1){
            $new_status = 0;
        }
        RoomType::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Room Type Status Updated Successfully'],200);
    }
    public function deleteRoomTypeData(Request $request){
        RoomType::where('id',$request->id)->delete();
        return response()->json(['success' => 'Room Type Deleted Successfully'],200);
    }
}
