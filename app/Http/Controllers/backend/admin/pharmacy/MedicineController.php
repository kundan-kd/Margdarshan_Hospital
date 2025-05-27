<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineGroup;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    public function index(){
        $categories = MedicineCategory::get();
        $companies = Company::get();
        $groups = MedicineGroup::get();
        $units = Unit::get();
        return view('backend.admin.modules.pharmacy.medicine',compact('categories','companies','groups','units'));
    }
    public function medicineView(Request $request){
        if($request->ajax()){
            $medicinecreate = Medicine::get();
            return DataTables::of($medicinecreate)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('category',function($row){
                return $row->categoryData->name;
            })
            ->addColumn('company',function($row){
                return $row->companyData->name;
            })
            ->addColumn('composition',function($row){
                return $row->composition;
            })
            ->addColumn('group',function($row){
                return $row->groupData->name;
            })
            ->addColumn('unit',function($row){
                return $row->unitData->unit;
            })
            ->addColumn('re_ordering_level',function($row){
                return $row->re_ordering_level;
            })
            ->addColumn('taxes',function($row){
                return $row->taxes;
            })
            ->addColumn('box_packing',function($row){
                return $row->box_packing;
            })
            ->addColumn('stock',function($row){
                return 'NA';
            })
            ->addColumn('action',function($row){
                 return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="medicineEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="medicineDelete('.$row->id.')"></iconify-icon>
                         </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    
     public function medicineAdd(Request $request){
        $validator = Validator::make($request->all(),[
        'category' => 'required',
        'company' => 'required',
        'group' => 'required',
        'unit' => 'required',
        're_order_level' => 'required',
        'rack' => 'required',
        'name' => 'required',
        'composition' => 'required',
        'taxes' => 'required',
        'box_pack' => 'required',
        'narration' => 'nullable',
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $medicines = new Medicine();
        $medicines->name = $request->name;
        $medicines->category_id = $request->category;
        $medicines->company_id = $request->company;
        $medicines->group_id = $request->group;
        $medicines->unit_id = $request->unit;
        $medicines->re_ordering_level = $request->re_order_level;
        $medicines->rack = $request->rack;
        $medicines->composition = $request->composition;
        $medicines->taxes = $request->taxes;
        $medicines->box_packing = $request->box_pack;
        $medicines->narration = $request->narration;
        if($medicines->save()){
            return response()->json(['success'=>'Medicine addedd successfully'],200);
        }else{
            return response()->json(['error_success'=>'Medicine not added'],500);
        }
     }
     public function getMedicineData(Request $request){
        $getData = Medicine::where('id',$request->id)->get();
        return response()->json(['success'=>'Medicin data fetched successfully','data'=>$getData],200);
     }
     public function updateMedicineData(Request $request){
       $update = Medicine::where('id',$request->id)->update([
        'category'=>$request->category,
        'company'=>$request->company, 
        'group'=>$request->group, 
        'unit'=>$request->unit, 
        're_ordering_level'=>$request->re_order_level, 
        'rack'=>$request->rack, 
        'name'=>$request->name, 
        'composition'=>$request->composition, 
        'taxes'=>$request->taxes, 
        'box_packing'=>$request->box_pack, 
        'narration'=>$request->narration, 
       ]);
       if($update){
         return response()->json(['success'=>'Medicine updated successfully'],200);
       }else{
            return response()->json(['error_success'=>'Medicine not updated'],500);
        }
     }
     public function deleteMedicineData(Request $request){
        Medicine::where('id',$request->id)->delete();
        return response()->json(['success'=>'Medicine deleted successfully'],200);
     }
}
