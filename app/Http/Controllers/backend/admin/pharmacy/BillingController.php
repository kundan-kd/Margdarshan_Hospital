<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\BloodType;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Patient;
use App\Models\PaymentMode;
use App\Models\PurchaseItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.billing');
    }
     public function billingAdd(){
        $categories = MedicineCategory::where('status',1)->get();
        $doctors = User::where('usertype_id',2)->where('status',1)->get();
        $patients = Patient::where('status',1)->get(['id','patient_id','name']);
        $bloodtypes = BloodType::where('status',1)->get();
        $paymentmodes = PaymentMode::where('status',1)->get();
        return view('backend.admin.modules.pharmacy.billing-add',compact('categories','doctors','patients','bloodtypes','paymentmodes'));
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
    public function billingAddDatas(Request $request){
    $validator = Validator::make($request->all(), [
        'billNo' => 'required',
        'patientID' => 'required',
        'resDoctor' => 'nullable',
        'outDoctor' => 'nullable',
        'notes' => 'nullable',
        'category' => 'required|array',
        'name' => 'required|array',
        'batchNo' => 'required|array',
        'expiry' => 'required|array',
        'qty' => 'required|array',
        'salesPrice' => 'required|array',
        'taxPer' => 'required|array',
        'taxAmount' => 'required|array',
        'amount' => 'required|array',
        'totalAmount' => 'required',
        'discountPer' => 'nullable',
        'totalDiscountAmount' => 'nullable',
        'totalTaxAmount' => 'required',
        'totalNetAmount' => 'required',
        'paymentMode' => 'nullable',
        'payAmount' => 'nullable'
    ]);

    if ($validator->fails()) {
        return response()->json(['error_validation' => $validator->errors()->all()], 200);
    }

    DB::beginTransaction();

    try {
        // Store purchase details
        $billings = new Billing();
        $billings->type = "medicine";
        $billings->bill_no = $request->billNo;
        $billings->patient_id = $request->patientID;
        $billings->res_doctor_id = $request->resDoctor;
        $billings->out_doctor_name = $request->outDoctor;
        $billings->naration = $request->notes;
        $billings->total_amount = $request->totalAmount;
        $billings->discount_per = $request->discountPer;
        $billings->discount_amount = $request->totalDiscountAmount;
        $billings->taxes = $request->totalTaxAmount;
        $billings->net_amount = $request->totalNetAmount;
        $billings->payment_mode = $request->paymentMode;
        $billings->paid_amount = $request->payAmount;
        $billings->due_amount = $request->totalNetAmount - $request->payAmount;

        if (!$billings->save()) {
            throw new \Exception("Failed to insert billing record");
        }

        // Store purchase items
        foreach ($request->category as $index => $category) {
            $billingItems = new BillingItem();
            $billingItems->billing_id = $billings->id;
            $billingItems->category_id = $category;
            $billingItems->name_id = $request->name[$index];
            $billingItems->batch_no = $request->batchNo[$index];
            $billingItems->expiry = $request->expiry[$index];
            $billingItems->qty = $request->qty[$index];
            $billingItems->sales_price = $request->salesPrice[$index];
            $billingItems->tax_per = $request->taxPer[$index];
            $billingItems->tax_amount = $request->taxAmount[$index];
            $billingItems->amount = $request->amount[$index];

            if (!$billingItems->save()) {
                throw new \Exception("Failed to insert billing item record");
            }
        }

        DB::commit();
        return response()->json(['success' => 'Medicine Billing done successfully'], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }

    }
}
