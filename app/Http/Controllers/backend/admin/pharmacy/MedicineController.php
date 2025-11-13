<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Composition;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineGroup;
use App\Models\PurchaseItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    public function index(){
        $categories = MedicineCategory::where('status',1)->get();
        $companies = Company::where('status',1)->get();
        $groups = MedicineGroup::where('status',1)->get();
        $units = Unit::where('status',1)->get();
        $compositions = Composition::where('status',1)->get();
        return view('backend.admin.modules.pharmacy.medicine',compact('categories','companies','groups','units','compositions'));
    }
    public function medicineView(Request $request){
        if($request->ajax()){
            if($request->name == null){
                $medicinecreate = Medicine::query();
            }else{
                $medicinecreate = Medicine::where('name','LIKE','%'.($request->name).'%')->get();
            }
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
                $compositionIds = explode(',', $row->composition);
                $compositionNames = \App\Models\Composition::whereIn('id', $compositionIds)->pluck('name')->toArray();
                return implode('<br>', $compositionNames);
            })
            // ->addColumn('group',function($row){
            //     return $row->groupData->name;
            // })
            ->addColumn('unit',function($row){
                return $row->unitData->unit;
            })
            ->addColumn('re_ordering_level',function($row){
                return $row->re_ordering_level;
            })
            ->addColumn('batch', function($row){
                $batch_nos = \App\Models\PurchaseItem::where('name_id', $row->id)->get(['batch_no', 'qty', 'return_qty', 'stock_out']);
                $output = '';
                foreach ($batch_nos as $item) {
                    if (($item->qty + $item->return_qty - $item->stock_out) > 0) {
                        $output .= $item->batch_no . '<br>';
                    }
                }
                return $output ?: 'NA';
            })
            ->addColumn('expiry', function($row){
                $expiry_date = \App\Models\PurchaseItem::where('name_id', $row->id)->get(['expiry', 'qty', 'return_qty', 'stock_out']);
                $outputs = '';
                foreach ($expiry_date as $item) {
                   if (($item->qty + $item->return_qty - $item->stock_out) > 0) {
                        $outputs .= $item->expiry . '<br>';
                    }
                }
                return $outputs ?: 'NA';
            })
             ->addColumn('hsn',function($row){
                return $row->hsn_number;
            })
            ->addColumn('stock',function($row){
                return (($row->stock_in + $row->return_qty) - $row->stock_out);
            })
            ->addColumn('action',function($row){
                 return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="iconamoon:eye-light" onclick="medicineDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="medicineEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="medicineDelete('.$row->id.')"></iconify-icon>
                         </a>-->';
            })
            ->rawColumns(['composition','batch','expiry','action'])
            ->make(true);
        }
    }
    
     public function medicineAdd(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
        'category' => 'required',
        'company' => 'required',
        'unit' => 'required',
        're_order_level' => 'required',
        'rack' => 'required',
        'name' => 'required',
        'composition' => 'array|required',
        'hsn' => 'required',
        'taxes' => 'required',
        'box_pack' => 'required',
        'narration' => 'nullable',
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $data[] = '';
        $medicines = new Medicine();
        $medicines->name = $request->name;
        $medicines->category_id = $request->category;
        $medicines->company_id = $request->company;
        // $medicines->group_id = $request->group;
        $medicines->unit_id = $request->unit;
        $medicines->re_ordering_level = $request->re_order_level;
        $medicines->rack = $request->rack;
        $medicines->composition = implode(",",$request->composition); //array to string conversion
        $medicines->hsn_number = $request->hsn;
        $medicines->taxes = $request->taxes;
        $medicines->box_packing = $request->box_pack;
        $medicines->narration = $request->narration;
        if($medicines->save()){
            $data = [
                'medicine_id' => $medicines->id,
                'medicine_name' => $request->name,
                'category_id' => $request->category,
                'taxes'=> $request->taxes
            ];  
            return response()->json(['success'=>'Medicine addedd successfully','data' => $data],200);
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
        'category_id'=>$request->category,
        'company_id'=>$request->company, 
        // 'group_id'=>$request->group, 
        'unit_id'=>$request->unit, 
        're_ordering_level'=>$request->re_order_level, 
        'rack'=>$request->rack, 
        'name'=>$request->name, 
        'composition'=>implode(",",$request->composition), // array to string conversion
        'hsn_number'=>$request->hsn, 
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
     public function medicineViewIndex($id){
        $medicines = Medicine::where('id',$id)->first();
        $purchaseItems = PurchaseItem::where('name_id',$id)->get();
        return view('backend.admin.modules.pharmacy.medicine-view',compact('medicines','purchaseItems'));
     }
     public function medicineLowInventory(){
        return view('backend.admin.modules.pharmacy.medicine-low-inventory');
     }
       public function medicineLowInventoryView(Request $request){
        if($request->ajax()){
             // Only get medicines where stock_in - stock_out is less than or equal to 0
            $medicineLow = DB::table('medicines')
            ->select('id', 'name', 'stock_in', 'stock_out', 're_ordering_level')
            ->whereRaw('(stock_in - stock_out) <= 0')
            ->orWhereRaw('(stock_in - stock_out) <= re_ordering_level')
            ->get();

            return DataTables::of($medicineLow)
            ->addColumn('name',function($row){
                return $row->name;
            })
            // ->addColumn('category',function($row){
            //     return $row->categoryData->name;
            // })
            // ->addColumn('company',function($row){
            //     return $row->companyData->name;
            // })
            // ->addColumn('composition',function($row){
            //     return $row->composition;
            // })
            // ->addColumn('unit',function($row){
            //     return $row->unitData->unit;
            // })
            // ->addColumn('hsn',function($row){
            //     return $row->hsn_number;
            // })
            ->addColumn('re_ordering_level',function($row){
                return $row->re_ordering_level;
            })
            ->addColumn('stock',function($row){
               $stock = $row->stock_in - $row->stock_out;
                return '<span style="color:red;">' . $stock . '</span>';

            })
            ->rawColumns(['stock'])
            ->make(true);
        }
    }
}
