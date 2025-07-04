<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\BedType;
use App\Models\RoomNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BedController extends Controller
{
    public function index(){
        $bedtypes = BedType::where('status',1)->get();
        $bedgroups = BedGroup::where('status',1)->get();
        return view('backend.admin.modules.master.bed',compact('bedtypes','bedgroups'));
    }
    public function viewBeds(Request $request){
        if($request->ajax()){
            $bed = Bed::get();
            return DataTables::of($bed)
            ->addColumn('bedNumber',function($row){
                return $row->bed_no;
            })
            ->addColumn('bedGroup',function($row){
                return $row->bedGroupData->name;
            })
            ->addColumn('bedType',function($row){
                return $row->bedTypeData->name;
            })
            ->addColumn('bedFloor',function($row){
                return $row->floor;
            })
            ->addColumn('amount',function($row){
                return $row->amount;
            })
            ->addColumn('current_status',function($row){
                return $row->current_status === 'vacant'? '<span class="text-success">Vacant</span>': '<span class="text-danger">Occupied</span>';
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="bedEdit('.$row->id.');getRoomNum('.$row->bed_group_id.','.$row->room_num_id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="bedDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['current_status','status','action'])
            ->make(true);
        }
    }
    public function getRoomNumber(Request $request){
        $getData = RoomNumber::where('room_group_id',$request->id)->where('status',1)->get();
        $getBedData = '';
        if($request->bed_id > 0){
            $getBedData = RoomNumber::where('id',$request->bed_id)->get();
        }
        return response()->json(['success'=>'Room Number data fetched','data'=>$getData,'bedData'=>$getBedData],200);
    }
    public function getBedDataDetails(Request $request){
        $bedFloors = '';
        if($request->bedFloor == -1){
            $bedFloors = 'UG';
        }else if($request->bedFloor == 0){
            $bedFloors = 'GF';
        }else if($request->bedFloor == 1){
            $bedFloors = '1F';
        }else if($request->bedFloor == 2){
            $bedFloors = '2F';
        }else if($request->bedFloor == 3){
            $bedFloors = '3F';
        }else if($request->bedFloor == 4){
            $bedFloors = '4F';
        }else if($request->bedFloor == 5){
            $bedFloors = '5F';
        }else if($request->bedFloor == 6){
            $bedFloors = '6F';
        }else if($request->bedFloor == 7){
            $bedFloors = '7F';
        }else{
            $bedFloors = 'TF';
        }
        $bedGroup = BedGroup::where('id',$request->bedGroup)->get(['name']);
        $roomNum = RoomNumber::where('id',$request->roomNumber)->get(['room_num']);
        $bedType = BedType::where('id',$request->bedType)->get(['name']);
        $bedData = [
            'floor'=>$bedFloors,
            'group'=>$bedGroup[0]->name ??'',
            'room'=>$roomNum[0]->room_num ??'',
            'type'=>$bedType[0]->name ??''
        ];
        return response()->json(['success'=>'Bed Data fetched','data'=>$bedData],200);
    }
    public function addBed(Request $request){
        $bed_check = Bed::where('bed_no',$request->bedNumber)->where('bed_group_id',$request->bedGroup)->where('bed_type_id',$request->bedType)->where('floor',$request->bedFloor)->where('room_num_id',$request->roomNum)->exists();
        if($bed_check == false){
            $validator = Validator::make($request->all(),[
                'bedNumber' => 'required',
                'amount' => 'required',
                'bedGroup' => 'required',
                'bedType' => 'required',
                'bedFloor' => 'required',
                'roomNum' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $bed = new bed();
            $bed->bed_no = $request->bedNumber;
            $bed->bed_group_id = $request->bedGroup;
            $bed->bed_type_id = $request->bedType;
            $bed->floor = $request->bedFloor;
            $bed->room_num_id = $request->roomNum;
            $bed->amount = $request->amount;
            if($bed->save()){
                return response()->json(['success'=>'bed added successfully'],200);
            }else{
                return response()->json(['error_success'=>'bed not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Bed confirguration already exists']);
        }
    }

    public function getBedData(Request $request){
        $getData = bed::where('id',$request->id)->get();
        return response()->json(['success'=>'Bed data fetched successfully','data'=>$getData],200);
    }
    public function updateBedData(Request $request)
{
    // Check for duplicate configuration in other records
    $bed_check = Bed::where('bed_no', $request->bedNumber)
        ->where('bed_group_id', $request->bedGroup)
        ->where('bed_type_id', $request->bedType)
        ->where('floor', $request->bedFloor)
        ->where('room_num_id', $request->roomNum)
        ->where('id', '!=', $request->id) // Exclude the current record
        ->exists();

    if (!$bed_check) {
        Bed::where('id', $request->id)->update([
            'bed_no'        => $request->bedNumber,
            'bed_group_id'  => $request->bedGroup,
            'bed_type_id'   => $request->bedType,
            'floor'         => $request->bedFloor,
            'room_num_id'   => $request->roomNum,
            'amount'        => $request->amount
        ]);
        return response()->json(['success' => 'Bed updated successfully'], 200);
    } else {
        return response()->json(['already_found' => 'This bed configuration already exists'], 409);
    }
}
    // public function updateBedData(Request $request){
    //     $bed_check = Bed::where('bed_no',$request->bedNumber)->where('bed_group_id',$request->bedGroup)->where('bed_type_id',$request->bedType)->where('floor',$request->bedFloor)->where('room_num_id',$request->roomNum)->exists();
    //     if($bed_check == false){
    //         bed::where('id',$request->id)->update([
    //             'bed_no' => $request->bedNumber,
    //             'bed_group_id' => $request->bedGroup,
    //             'bed_type_id' => $request->bedType,
    //             'floor' => $request->bedFloor,
    //             'room_num_id' => $request->roomNum,
    //             'amount' => $request->amount
    //         ]);
    //         return response()->json(['success' => 'Bed updated successfully'],200);
    //     }else{
    //         return response()->json(['already_found'=>'This Bed confirguration already exists']);
    //     }    
    // }
    public function statusUpdate(Request $request){
        $bed_status = bed::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($bed_status[0]->status == 1){
            $new_status = 0;
        }
        bed::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Bed Status Updated Successfully'],200);
    }
    public function deleteBedData(Request $request){
        bed::where('id',$request->id)->delete();
        return response()->json(['success' => 'Bed Deleted Successfully'],200);
    }
}
