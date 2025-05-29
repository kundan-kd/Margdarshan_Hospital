<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\PurchaseItem;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.billing');
    }
     public function billingAdd(){
        $categories = MedicineCategory::where('status',1)->get();
        $doctors = User::where('usertype_id',2)->where('status',1)->get();
        return view('backend.admin.modules.pharmacy.billing-add',compact('categories','doctors'));
    }
    public function getMedicineNames(Request $request){
        // dd($request->all());
        $getData = Medicine::where('category_id',$request->id)->get();
        return response()->json(['success'=>'Medicine data found','data'=>$getData],200);
    }
    public function getBatchNumbers(Request $request){
        $getData = PurchaseItem::where('name_id',$request->id)->get();
        return response()->json(['success'=>'Batch number found','data'=>$getData],200);
    }
    public function getBatchExpiryDate(Request $request){
        $getData = PurchaseItem::where('id',$request->id)->get();
        return response()->json(['success'=>'Batch expity found','data'=>$getData],200);
    }
}
