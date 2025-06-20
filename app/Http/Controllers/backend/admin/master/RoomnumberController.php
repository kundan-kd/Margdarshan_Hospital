<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\RoomNumber;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomnumberController extends Controller
{
    public function index(){
        $roomTypes = RoomType::where('status',1)->get();
        return view('backend.admin.modules.master.room-number',compact('roomTypes'));
    }
    public function viewRoomNums(Request $request){
        if($request->ajax()){
            $roomnum = RoomNumber::get();
            return DataTables::of($roomnum)
            ->addColumn('roomtype',function($row){
                return $row->roomtypeData->name;
            })
            ->addColumn('roomnum',function($row){
                return $row->room_num;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="roomNumEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="roomNumDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addRoomNum(Request $request){
        $room_check = RoomNumber::where('roomtype_id',$request->roomType)->where('room_num',$request->roomNum)->exists();
        if($room_check == false){
            $validator = Validator::make($request->all(),[
                'roomType' => 'required',
                'roomNum' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $roomnum = new RoomNumber();
            $roomnum->roomtype_id = $request->roomType;
            $roomnum->room_num = $request->roomNum;
            if($roomnum->save()){
                return response()->json(['success'=>'Room Number added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Room Number not added'],500);
            }
        }else{
            return response()->json(['already_found' => 'This Room configuration already exists'],200);
        }    
    }

    public function getRoomNumData(Request $request){
        $getData = RoomNumber::where('id',$request->id)->get();
        return response()->json(['success'=>'Room Number data fetched successfully','data'=>$getData],200);
    }
    public function updateRoomNumData(Request $request){
            RoomNumber::where('id',$request->id)->update([
                'roomtype_id' => $request->roomType,
                'room_num' => $request->roomNum
            ]);
            return response()->json(['success' => 'Room Number updated successfully'],200);
           
    }
    public function statusUpdate(Request $request){
        $roomnum_status = RoomNumber::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($roomnum_status[0]->status == 1){
            $new_status = 0;
        }
        RoomNumber::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Room Number Status Updated Successfully'],200);
    }
    public function deleteRoomNumData(Request $request){
        RoomNumber::where('id',$request->id)->delete();
        return response()->json(['success' => 'Room Number Deleted Successfully'],200);
    }
}
