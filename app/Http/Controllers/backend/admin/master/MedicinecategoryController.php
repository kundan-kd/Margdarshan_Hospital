<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MedicinecategoryController extends Controller
{
    public function index()
    {
        return view('backend.admin.modules.master.medicine-category');
    }
    public function viewMedicineCategory(Request $request)
    {
         if($request->ajax()){
            $usertype = MedicineCategory::get();
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
                  <iconify-icon icon="lucide:edit" onclick="medicineCategoryEdit('.$row->id.')"></iconify-icon>
                </a>
                <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="medicineCategoryDelete('.$row->id.')"></iconify-icon>
                </a>-->';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addMedicineCategory(Request $request){
        $check_medCat = MedicineCategory::where('name',$request->category)->exists();
        if($check_medCat == false){
            $validator = Validator::make($request->all(),[
                'category' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['error_validation'=> $validator->errors()->all(),],422);
            }
            $medicinecategory = new MedicineCategory();
            $medicinecategory->name = $request->category;
            if($medicinecategory->save()){
                return response()->json(['success'=>'Medicine Category added successfully'],201);
            }else{
                return response()->json(['error_success'=>'Medicine Category not added'],500);
            }
        }else{
            return response()->json(['already_found'=>'This Medicine Category already found'],200);
        }    
    }
    public function getMedicineCategoryData(Request $request){
        $getData = MedicineCategory::where('id',$request->id)->get();
        return response()->json(['success'=>'Medicine Category fetched successfully','data'=>$getData],200);
    }
    public function updateMedicineCategoryData(Request $request){
        $check_medCat = MedicineCategory::where('name',$request->category)->exists();
        if($check_medCat == false){
            MedicineCategory::where('id',$request->id)->update([
                'name' => $request->category
            ]);
            return response()->json(['success' => 'Payment Mode updated successfully'],200);
        }else{
            return response()->json(['already_found'=>'This Medicine Category already found'],200);
        }       
    }
    public function statusUpdate(Request $request){
        $MCstatus = MedicineCategory::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($MCstatus[0]->status == 1){
            $new_status = 0;
        }
        MedicineCategory::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Medicine Category Status Updated Successfully'],200);
    }
    public function deleteMedicineCategory(Request $request){
        MedicineCategory::where('id',$request->id)->delete();
        return response()->json(['success' => 'Medicine Category Deleted Successfully'],200);
    }
}
