<?php

namespace App\Http\Controllers\backend\admin\sales;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDescription;
use App\Models\NarationList;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
   public function analytics(){
    $leads = Lead::count();
    $convertedLeads = Lead::where('lead_status','Converted')->count();
    $assignLeads = Lead::whereNotNull('assign_to')->count();
    $UnAssignLeads = Lead::whereNull('assign_to')->count();
    $now = Carbon::now();
    $formattedDate = $now->toDateString(); // Output: "2025-07-16"
    $todayFollowup = Lead::where('next_followup_date',$formattedDate)->get();
    $duefollowup = Lead::whereNull('naration')->get();
    return view('backend.admin.modules.sales.lead-dashboard', compact('leads','convertedLeads','assignLeads','UnAssignLeads','todayFollowup','duefollowup'));
}
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
        'pin' =>'required'
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
//  public function addBulkLead(Request $request){
//     $validator = Validator::make($request->all(), [
//         'name'    => 'required|array',
//         'mobile'  => 'required|array',
//         'source'  => 'required|array',
//         'address' => 'required|array',
//         'city'    => 'required|array',
//         'state'   => 'required|array',
//         'pin'     => 'required|array',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error_validation' => $validator->errors()->all(),], 422);
//     }

//     $leadData = [];

//     foreach ($request->name as $index => $name) {
//         $leadData[] = [
//             'name'    => $name,
//             'mobile'  => $request->mobile[$index],
//             'source'  => $request->source[$index],
//             'address' => $request->address[$index],
//             'city'    => $request->city[$index],
//             'state'   => $request->state[$index],
//             'pin'     => $request->pin[$index],
//             'created_by'    => Auth::id(),
//             'created_at' => now(),
//             'updated_at' => now(),
//         ];
//     }
//     $leadInsert = Lead::insert($leadData); // insert input bulk array data in db at once
//     if($leadInsert){
//         return response()->json(['success' => 'Bulk lead added successfully'], 200);
//     }else{
//         return response()->json(['error_success' => 'Bulk lead not added']);
//     }

// }
public function addBulkLead(Request $request)
{
    // Validate that 'leads' is an array and each item has required fields
    $validator = Validator::make($request->all(), [
        'leads' => 'required|array',
        'leads.*.name'    => 'required|string',
        'leads.*.mobile'  => 'required|string',
        'leads.*.source'  => 'required|string',
        'leads.*.address' => 'required|string',
        'leads.*.city'    => 'required|string',
        'leads.*.state'   => 'required|string',
        'leads.*.pin'     => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error_validation' => $validator->errors()->all(),
        ], 422);
    }

    $leadData = [];

    foreach ($request->leads as $lead) {
        $leadData[] = [
            'name'       => $lead['name'],
            'mobile'     => $lead['mobile'],
            'source'     => $lead['source'],
            'address'    => $lead['address'],
            'city'       => $lead['city'],
            'state'      => $lead['state'],
            'pin'        => $lead['pin'],
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    $leadInsert = Lead::insert($leadData);

    if ($leadInsert) {
        return response()->json(['success' => 'Bulk lead added successfully'], 200);
    } else {
        return response()->json(['error_success' => 'Bulk lead not added']);
    }
}
    public function csvUpload(Request $request){
      // Validate the uploaded file
    $validator = Validator::make($request->all(), [
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $file = $request->file('csv_file');

    // Open and read the CSV file
    $data = [];
    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        $header = fgetcsv($handle); // Read the first row as header
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = array_combine($header, $row); // Combine header with row values
        }
        fclose($handle);
    }

    // Now $data contains all CSV rows as associative arrays
    return response()->json([
        'success' => 'CSV processed successfully',
        'data' => $data
    ]);

        
    }


    public function leadCenter(){
    $leads = Lead::get();
    $salesTeamMember = User::where('status',1)->where('usertype_id',7)->get(['id','name','sales_team_id']);
     return view('backend.admin.modules.sales.lead-center',compact('leads','salesTeamMember'));
    }
   public function viewSingleAssignLeads(Request $request){
      if($request->ajax()){
           $leads = Lead::whereNull('assign_to')->get();
            return DataTables::of($leads)
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
           ->addColumn('assign_to', function ($row) {
                $user = $row->userData;
                if (!$user || !$user->sales_team_id) {
                    return $user->name ?? 'NA';
                }
                $teamName = Team::where('id', $user->sales_team_id)->value('name');
                return $user->name . ' (' . ($teamName ?? 'No Team') . ')';
            })
            // ->addColumn('assign_date',function($row){
            //     return $row->assign_date ? date('d-M-Y', strtotime($row->assign_date)) : 'NA';
            // })
            ->addColumn('action',function($row){
                return '<td>
                          <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick="singleLeadAssign('.$row->id.')">
                              <i class="ri-team-line"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-title="Assign To"></i>
                          </button>
                          
                          <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick=trashLead('.$row->id.')>
                            <iconify-icon icon="mingcute:delete-2-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-title="Move to Trash"></iconify-icon>
                          </button>
                       </td>';
            })
          
            ->rawColumns(['select','action'])
            ->make(true);
        }
   }
public function assignSingleLead(Request $request){
    $update = Lead::where('id', $request->lead_id)->update([
        'assign_to'   => $request->user_id,
        'assign_date' => Carbon::now(),
        'assign_by'   => Auth::id(),
    ]);
        if($update){
            return response()->json(['success'=> 'Lead assigned successfully'], 200);
        }else{
            return response()->json(['error_success' => 'Lead not assigned']);
        }
}    
public function trashLeadData(Request $request){
    $delete = Lead::where('id',$request->id)->delete();
    if($delete){
          return response()->json(['success'=> 'Lead moved to trash successfully'], 200);
    }else{
         return response()->json(['success'=> 'Lead not deleted']);
    }
}
public function bulkLeadAssignPage(){
      $leads = Lead::get();
    $salesTeamMember = User::where('status',1)->where('usertype_id',7)->get(['id','name','sales_team_id']);
     return view('backend.admin.modules.sales.bulk-lead-center',compact('leads','salesTeamMember'));
}

   public function viewBulkAssignLeads(Request $request){
      if($request->ajax()){
            $leadss = Lead::whereNull('assign_to')->get();
            return DataTables::of($leadss)
             ->addColumn('select',function($row){
                $visibility = $row->assign_to == NULL ? '':'disabled';
                return '<div class="d-flex align-items-center gap-10">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-400" id="leadId" name="leadId[]" type="checkbox" name="checkbox" value="'.$row->id.'" '.$visibility.'>
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
           ->addColumn('assign_to', function ($row) {
                $user = $row->userData;
                if (!$user || !$user->sales_team_id) {
                    return $user->name ?? 'NA';
                }
                $teamName = Team::where('id', $user->sales_team_id)->value('name');
                return $user->name . ' (' . ($teamName ?? 'No Team') . ')';
            })       
            ->rawColumns(['select'])
            ->make(true);
        }
   }

    public function assignBulkLeads(Request $request){
        $assignData = [
            'assign_to'   => $request->user_id,
            'assign_date' => Carbon::now(),
            'assign_by'   => Auth::id(),
        ];
        $update = Lead::whereIn('id', $request->lead_id)->update($assignData);
        if($update){
            return response()->json(['success'=> 'Bulk Leads assigned successfully'], 200);
        }else{
            return response()->json(['error_success' => 'Bulk Leads not assigned']);
        }
    }
    public function trash(){
         return view('backend.admin.modules.sales.trash');
    }
    public function viewTrashLead(Request $request){
      if($request->ajax()){
            $leadss = Lead::onlyTrashed()->get();
            return DataTables::of($leadss)
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
           ->addColumn('assign_to', function ($row) {
                $user = $row->userData;
                if (!$user || !$user->sales_team_id) {
                    return $user->name ?? 'NA';
                }
                $teamName = Team::where('id', $user->sales_team_id)->value('name');
                return $user->name . ' (' . ($teamName ?? 'No Team') . ')';
            })
            ->addColumn('deleted_at', function ($row) {
                return $row->deleted_at
                    ? Carbon::parse($row->deleted_at)->timezone('Asia/Kolkata')->format('d-M-Y h:i A')
                    : '—';
            })
            ->addColumn('action',function($row){
                return '<td>
                 <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick=unTrashLead('.$row->id.')>
                            <iconify-icon icon="mingcute:restore-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-title="Untrash Lead"></iconify-icon>
                          </button>
                       </td>';
            })
            ->rawColumns(['select','action'])
            ->make(true);
        }
    }
    public function processDesk(){
       $salesTeamMember = User::where('status',1)->where('usertype_id',7)->get(['id','name','sales_team_id']);
       $narationList = NarationList::where('status',1)->get();
        return view('backend.admin.modules.sales.process-desk',compact('salesTeamMember','narationList'));
    }
    public function viewProcessDeskLeads(Request $request){
        if($request->ajax()){
           $leads = Lead::whereNotNull('assign_to')->get();
            return DataTables::of($leads)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('mobile',function($row){
                return $row->mobile;
            })
            ->addColumn('address',function($row){
                return $row->city.', '.$row->state.'-'.$row->pin;
            })
            ->addColumn('assign_to', function ($row) {
                $user = $row->userData;
                if (!$user || !$user->sales_team_id) {
                    return $user->name ?? 'NA';
                }
                $teamName = Team::where('id', $user->sales_team_id)->value('name');
                return $user->name . ' (' . ($teamName ?? 'No Team') . ')';
            })
            // ->addColumn('assign_date',function($row){
            //      return $row->assign_date
            //         ? Carbon::parse($row->assign_date)->timezone('Asia/Kolkata')->format('d-M-Y h:i A')
            //         : '—';
            // })
            ->addColumn('naration', function($row) {
                $narationText = $row->narationListData->naration ?? 'NA';
                return '<a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#narationView" onclick="getPrevNarations('.$row->id.')">'
                     . $narationText .
                     '</a>';
            })

            ->addColumn('follow_up',function($row){
                // return $row->next_followup_date ? Carbon::parse($row->assign_date)->timezone('Asia/Kolkata')->format('d-M-Y'): 'NA';
                return $row->next_followup_date ? date('d/m/Y', strtotime($row->next_followup_date)) : 'NA';
            })
            ->addColumn('lead_status',function($row){
                 return $row->lead_status === 'Pending'? '<span class="badge text-sm fw-normal text-danger-600 bg-danger-100 px-18 py-8 radius-4 text-white">Pending</span>': '<span class="badge text-sm fw-normal text-success-600 bg-success-100 px-18 py-8 radius-4 text-white">Converted</span>';                
            })
            ->addColumn('action',function($row){
                return '<td>
                            <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#add-narration" onclick="addNaration('.$row->id.')">
                                <i class="ri-sticky-note-add-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-title="Add Narration"></i>
                            </button>
                             <button class="mx-1 bg-info-200 bg-hover-info-300 text-dark-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick="followup('.$row->id.')">
                                <i class="ri-arrow-right-double-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-title="Next Follow up Date"></i>
                            </button>
                            <button class="mx-1 bg-warning-200 bg-hover-warning-300 text-warning-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick="transferTo('.$row->id.')">
                                <i class="ri-truck-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-warning" data-bs-title="Transfer To"></i>
                            </button>
                            <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick="deleteLead('.$row->id.')">
                                <iconify-icon icon="mingcute:delete-2-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-title="Move to Trash"></iconify-icon>
                            </button>
                        </td>';
            })          
            ->rawColumns(['select','naration','lead_status','action'])
            ->make(true);
        }
    }
    public function narationAdd(Request $request){
        $narations = new LeadDescription();
        $narations->lead_id = $request->lead_id;
        $narations->naration_list_id = $request->naration_list;
        $narations->naration = $request->naration;
        if($narations->save()){
            Lead::where('id',$request->lead_id)->update([
                'naration_list_id' => $request->naration_list,
                'naration' => $request->naration
            ]);
            return response()->json(['success'=>'Naration added successfully'],200);
        }else{
            return response()->json(['error_success'=>'Naration not added']);
        }
    }
    public function getNarationData(Request $request){
        $getData = LeadDescription::with('narationList')->where('lead_id',$request->id)->orderBy('id','desc')->get();
        return response()->json(['success'=>'Naration data fetched','data'=>$getData],200);
    }
    public function tranferToDataSubmit(Request $request){
        $prevAssignId = Lead::where('id',$request->lead_id)->pluck('assign_to');
        $update = Lead::where('id', $request->lead_id)->update([
            'assign_to' => $request->team_id,
            'assign_transfer_reason' => $request->reason,
            'assign_transfer_date' => Carbon::now(),
            'previous_assign_to' => $prevAssignId[0]
        ]);
        if($update){
            return response()->json(['success'=> 'Lead transfered successfully'], 200);
        }else{
            return response()->json(['error_success' => 'Lead not transfered']);
        }
    }
    public function followupDateSubmit(Request $request){
        $convertedDate = Carbon::createFromFormat('d-m-Y', $request->fdate)->format('Y-m-d');
        $update_follow = Lead::where('id', $request->lead_id)->update([
            'next_followup_date' => $convertedDate
        ]);
        if($update_follow){
            return response()->json(['success'=> 'follow up date added successfully'], 200);
        }else{
            return response()->json(['error_success' => 'follow up date not added']);
        }
    }
    public function restoreLeadData(Request $request){
        $update_restore = Lead::withTrashed()->where('id', $request->id)->restore();
        if($update_restore){
            return response()->json(['success'=> 'Lead restored successfully'], 200);
        }else{
            return response()->json(['error_success' => 'Lead not restored']);
        }
    }
}
