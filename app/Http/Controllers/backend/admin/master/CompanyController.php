<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
     public function index()
    {
        return view('backend.admin.modules.master.company');
    }
    public function viewCompany(Request $request)
    {
         if($request->ajax()){
            $usertype = Company::get();
            return DataTables::of($usertype)
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
                  <iconify-icon icon="lucide:edit" onclick="companyEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="companyDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addCompany(Request $request){
        $check_company = Company::where('name',$request->company)->exists();
        if($check_company == false){
            $validator = Validator::make($request->all(),[
                'company' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $companys = new Company();
            $companys->name = $request->company;
            if($companys->save()){
                return response()->json(['success'=>'Company added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Company Category not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Company already found'],200);
        }
    }
    public function getCompanyData(Request $request){
        $getData = Company::where('id',$request->id)->get();
        return response()->json(['success'=>'Company data fetched successfully','data'=>$getData],200);
    }
    public function updateCompanyData(Request $request){
        $check_company = Company::where('name',$request->company)->exists();
        if($check_company == false){
            Company::where('id',$request->id)->update([
                'name' => $request->company
            ]);
            return response()->json(['success' => 'Company updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Company already found'],200);
        }
    }
    public function statusUpdate(Request $request){
        $companystatus = Company::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($companystatus[0]->status == 1){
            $new_status = 0;
        }
        Company::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Company Status Updated Successfully'],200);
    }
    public function deleteCompany(Request $request){
        Company::where('id',$request->id)->delete();
        return response()->json(['success' => 'Company Deleted Successfully'],200);
    }
}
