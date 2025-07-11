<?php

namespace App\Http\Controllers\backend\admin\sales;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
   public function lead(){
    $teams = Team::where('status',1)->get();
     return view('backend.admin.modules.sales.lead-add',compact('teams'));
   }
   public function addLead(Request $request){
    $validator = Validator::make($request->all(),[
        'name' =>'required',
        'mobile' =>'required',
        'source' =>'required',
        'address' =>'required',
        'city' =>'required',
        'state' =>'required',
        'pin' =>'required',
        'team' =>'required'
    ]);
    if($validator->fails()){
        return response()->json(['error_validation'=>$validator->errors()->all(),],200);
    }
    $leads = new Lead();
    $leads->name = $request->name;
    $leads->mobile = $request->mobile;
    $leads->source = $request->source;
    $leads->address = $request->address;
    $leads->city = $request->city;
    $leads->state = $request->state;
    $leads->pin = $request->pin;
    $leads->team = $request->team;
    $leads->created_by = Auth::id();
    if($leads->save()){
        return response()->json(['success'=>'Lead added successfuly'],200);
    }else{
        return response()->json(['error_success'=>'Lead not added']);
    }
   }
   public function bulkLead(){
     return view('backend.admin.modules.sales.bulk-lead-add');
   }
 public function addBulkLead(Request $request){
    $validator = Validator::make($request->all(), [
        'name'    => 'required|array',
        'mobile'  => 'required|array',
        'source'  => 'required|array',
        'address' => 'required|array',
        'city'    => 'required|array',
        'state'   => 'required|array',
        'pin'     => 'required|array',
        'team'    => 'required|array',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error_validation' => $validator->errors()->all(),
        ], 422);
    }

    $leadData = [];

    foreach ($request->name as $index => $name) {
        $leadData[] = [
            'name'    => $name,
            'mobile'  => $request->mobile[$index],
            'source'  => $request->source[$index],
            'address' => $request->address[$index],
            'city'    => $request->city[$index],
            'state'   => $request->state[$index],
            'pin'     => $request->pin[$index],
            'team'    => $request->team[$index],
            'created_by'    => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    Lead::insert($leadData); // insert input bulk array data in db at once

    return response()->json([
        'success' => 'Bulk lead added successfully',
    ], 200);
}
 public function leadCenter(){
    $leads = Lead::get();
     return view('backend.admin.modules.sales.lead-center',compact('leads'));
   }
   public function viewLeads(Request $request){
      if($request->ajax()){
            $leads = Lead::get();
            return DataTables::of($leads)
            ->addColumn('select',function($row){
                return '<div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400" type="checkbox" name="checkbox">
                                        </div>
                                    </div>';
            })
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('mobile',function($row){
                return $row->mobile;
            })
            ->addColumn('source',function($row){
                return $row->source;
            })
            ->addColumn('address',function($row){
                return $row->address;
            })
            ->addColumn('city',function($row){
                return $row->city;
            })
            ->addColumn('state',function($row){
                return $row->state;
            })
            ->addColumn('pin',function($row){
                return $row->pin;
            })
            ->addColumn('team',function($row){
                return $row->team;
            })
          
            ->rawColumns(['select'])
            ->make(true);
        }
   }

}
