<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Department;
use App\Models\RoomNumber;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function index(){
    // This method returns the view for managing users
    // It can be used to load any necessary data or configurations before rendering the view    
    $departments = Department::where('status', 1)->get();
    $userTypes = UserType::where('status', 1)->get();
    $bloodTypes = BloodType::where('status', 1)->get();
    $opd_rooms = RoomNumber::where('status', 1)->where('current_status','vacant')->where('roomtype_id', 6)->get();
    return view('backend.admin.modules.master.user', compact('departments', 'userTypes', 'bloodTypes','opd_rooms'));
    }
    public function viewUsers(Request $request){
          if($request->ajax()){
            // Eager load relationships to avoid null errors
            $users = User::with(['departmentData', 'userTypeData'])->get();
            return datatables()::of($users)
            ->addColumn('staff_id',function($row){
                return $row->staff_id;
            })
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
            ->addColumn('department', function($row) {
                return optional($row->departmentData)->name ?? 'N/A';
            })
            ->addColumn('usertype', function($row) {
                return optional($row->userTypeData)->name ?? 'N/A';
            })

            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="userEdit('.$row->id.');opdRoomData('.$row->id.')"></iconify-icon>
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
        // dd($request->all());
        if($request->ajax()){
            $validator = Validator::make($request->all(), [
                'departmentID' => 'required',
                'userType' => 'required',
                'bloodType' => 'required',
                'fee' => 'nullable',
                'opdRoom' => 'nullable',
                'name' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'dob' => 'required',
                'doj' => 'required',
                'pan' => 'required',
                'adhar' => 'required',
                'mobile' => 'required',
                'email' => 'required',
                'pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $month = date('m'); // Gets the current month (e.g., "05")
            $year = date('y'); // Gets the current year (e.g., "25")
            $user = new User();
            $user->department_id = $request->departmentID;
            $user->usertype_id = $request->userType;
            $user->bloodtype_id = $request->bloodType;
            $user->fee = $request->fee ?? 0; // Default to 0 if fee is not provided
            $user->room_number = $request->opdRoom ?? null; // Default to null if opdRoom is not provided
            $user->name = $request->name;
            $user->fname = $request->fname;
            $user->mname = $request->mname;
            $user->dob = $request->dob;
            $user->doj = $request->doj;
            $user->pan = $request->pan;
            $user->adhar = $request->adhar;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->password = Hash::make($request->pass);
            $user->plain_password = $request->pass;
            if($user->save()){
                $user->staff_id = "MHST". $month.$year.$user->id;
                $user->save();
                RoomNumber::where('id',$request->opdRoom)->update([
                    'current_status' => 'occupied',
                    'occupied_by' => 'doctor',
                    'occupied_by_id' => $user->id
                ]);
                return response()->json(['success' => 'User created successfully.']);
            } else {
                return response()->json(['error' => 'Failed to create user.'], 500);
            }
        }
    }
    public function getUserData(Request $request){
        if($request->ajax()){
            $getData = User::where('id', $request->id)->get();
            $getRooms = RoomNumber::where('id',$getData[0]->room_number)->get(['id','room_num']);
            if($getData){
                return response()->json(['success' => 'User data fetched successfully', 'data' => $getData,'roomData' => $getRooms], 200);
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        }
    }
    public function updateUser(Request $request){
            $old_opdRoom = User::where('id', $request->id)->value('room_number');
            $hash = Hash::make($request->pass);
            $update = User::where('id', $request->id)->update([
                'department_id' => $request->departmentID,
                'usertype_id' => $request->userType,
                'bloodtype_id' => $request->bloodType,
                'fee' => $request->fee ?? 0,
                'room_number' => $request->opdRoom ?? null,
                'name' => $request->name,
                'fname' => $request->fname,
                'mname' => $request->mname,
                'dob' => $request->dob,
                'doj' => $request->doj,
                'pan' => $request->pan,
                'adhar' => $request->adhar,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => $hash,
                'plain_password' => $request->pass
            ]);
            if($update){
                RoomNumber::where('id',$request->opdRoom)->update([
                    'current_status' => 'occupied',
                    'occupied_by' => 'doctor',
                    'occupied_by_id' => $request->id
                ]);
                if($request->opdRoom == null || $request->opdRoom ==''){
                    RoomNumber::where('id',$old_opdRoom)->update([
                        'current_status' => 'vacant',
                        'occupied_by' => NULL,
                        'occupied_by_id' => NULL
                    ]);
                }
                return response()->json(['success' => 'User updated successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to update user'], 500);
            }
    }
    public function deleteUserData(Request $request){
        if($request->ajax()){
            $delete = User::where('id', $request->id)->delete();
            if($delete){
                return response()->json(['success' => 'User deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete user'], 500);
            }
        }
    }
    public function getOPDRoom(Request $request){
        $getRoomNum = User::where('id', $request->id)->get('room_number');
        $getRoomData = RoomNumber::where('id', $getRoomNum[0]->room_number)->get(['id','room_num']);
        $getData = RoomNumber::where('status', 1)->where('current_status','vacant')->where('roomtype_id', 6)->get();
        return response()->json(['success' => 'OPD Rooms fetched successfully', 'data' => $getData,'roomData'=>$getRoomData], 200);
    }
}
