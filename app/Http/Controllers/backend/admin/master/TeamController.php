<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TeamController extends Controller
{
    public function index(){
         return view('backend.admin.modules.master.team');
    }
     public function viewTeams(Request $request){
        if($request->ajax()){
            $teams = Team::get();
            return DataTables::of($teams)
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
                  <iconify-icon icon="lucide:edit" onclick="teamEdit('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addTeam(Request $request){
        $check_team = Team::where('name',$request->team)->exists();
        if($check_team == false){
            $validator = Validator::make($request->all(),[
                'team' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $team = new Team();
            $team->name = $request->team;
            if($team->save()){
                return response()->json(['success'=>'Team added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Team not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Team already found'],200);
        }
    }

    public function getTeamData(Request $request){
        $getData = Team::where('id',$request->id)->get();
        return response()->json(['success'=>'Team data fetched successfully','data'=>$getData],200);
    }
    public function updateTeamData(Request $request){
        $check_team = Team::where('name',$request->team)->where('id', '!=', $request->id)->exists();
        if($check_team == false){
            Team::where('id',$request->id)->update([
                'name' => $request->team
            ]);
            return response()->json(['success' => 'Team updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Team already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $team_status = Team::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($team_status[0]->status == 1){
            $new_status = 0;
        }
        Team::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'User Type Status Updated Successfully'],200);
    }
}
