<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.billing');
    }
     public function billingAdd(){
       $medicines = Medicine::with('categoryData')->get();

        return view('backend.admin.modules.pharmacy.billing-add',compact('medicines'));
    }
    public function getMedicineNames(Request $request){
        // dd($request->all());
        $getData = Medicine::where('category_id',$request->id)->get();
        return response()->json(['success'=>'Medicine data found','data'=>$getData],200);
    }
}
