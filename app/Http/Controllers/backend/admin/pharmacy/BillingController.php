<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\BillingPayment;
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
use Yajra\DataTables\Facades\DataTables;

class BillingController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.billing');
    }
    public function billingView(Request $request){
        if($request->ajax()){
            $purchase = Billing::get();
            return DataTables::of($purchase)
            ->addColumn('patient',function($row){
                return $row->patientData->name ??'Cash';
            })
            ->addColumn('created_at',function($row){
                return $row->created_at;
            })
            ->addColumn('bill_no',function($row){
                return $row->bill_no;
            })
            ->addColumn('total',function($row){
                return $row->total_amount ?? 0;
            })
            ->addColumn('discount',function($row){
                return $row->total_discount ?? 0;
            })
            ->addColumn('net_amount',function($row){
                return $row->net_amount ?? 0;
            })
            ->addColumn('paid_amount',function($row){
                return $row->paid_amount ?? 0;
            })
            ->addColumn('action',function($row){

                // $encryptedId = Crypt::encryptString($row->id);
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="iconamoon:eye-light" onclick="purchaseDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="billingEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="purchaseDelete('.$row->id.')"></iconify-icon>
                         </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
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
        $billingItemData = BillingItem::where('id',$request->billingItemID)->get();
        return response()->json(['success'=>'Medicine data found','data'=>$getData,'billingItem'=>$billingItemData],200);
    }
    public function getBatchNumbers(Request $request){
        $getPurchaseData = PurchaseItem::where('name_id',$request->id)->get();
        $getBillingData = BillingItem::where('name_id',$request->id)->get();
        return response()->json(['success'=>'Batch number found','data'=>$getPurchaseData,'billingData'=>$getBillingData],200);
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
                PurchaseItem::where('id',$request->batchNo[$index])->update([
                    'stock_out' => $request->qty[$index]
                ]);
            }

            $billingPayments = new BillingPayment();
            $billingPayments->billing_id = $billings->id;
            $billingPayments->payment_mode_id = $request->paymentMode;
            $billingPayments->amount = $request->payAmount;
            if (!$billingPayments->save()) {
                    throw new \Exception("Failed to insert billing payment record");
                }

            DB::commit();
            return response()->json(['success' => 'Medicine Billing done successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function billingEditPage($id){
        $billings = Billing::where('id',$id)->get();
        $billingItems = BillingItem::where('billing_id',$id)->get();
        $categories = MedicineCategory::where('status',1)->get();
        $doctors = User::where('usertype_id',2)->where('status',1)->get();
        $patients = Patient::where('status',1)->get(['id','patient_id','name']);
        $bloodtypes = BloodType::where('status',1)->get();
        $paymentmodes = PaymentMode::where('status',1)->get();
        //dd($billingItems);
        return view('backend.admin.modules.pharmacy.billing-edit',compact('billings','billingItems','categories','doctors','patients','bloodtypes','paymentmodes'));
    }
    // public function billingEditAutoLoadData(Request $request){
    //     $billings = Billing::where('id',$request->id)->get();
    //     $billingItems = BillingItem::where('billing_id',$request->id)->get();
    //     $categories = MedicineCategory::where('status',1)->get();
    //     $doctors = User::where('usertype_id',2)->where('status',1)->get();
    //     $patients = Patient::where('status',1)->get(['id','patient_id','name']);
    //     $bloodtypes = BloodType::where('status',1)->get();
    //     $paymentmodes = PaymentMode::where('status',1)->get();
    //     $data = [
    //         'billings'=>$billings,
    //         'billingItems'=>$billingItems,
    //         'categories'=>$categories,
    //         'doctors'=>$doctors,
    //         'patients'=>$patients,
    //         'bloodtypes'=>$bloodtypes,
    //         'paymentmodes'=>$paymentmodes,
    //     ];
    //     return response()->json(['success'=>'Billing edit data found','getData'=>$data],200);
    // }
    public function getBillingNamesSelectEdit(Request $request){
        $getMedicineData = Medicine::where('category_id',$request->catValue)->get();
        $getNameData = BillingItem::where('id',$request->billingID)->get(['name_id','batch_no']);
        $batch_no = PurchaseItem::where('name_id',$getNameData[0]->name_id)->get();
        $data = ['medicines'=>$getMedicineData,'itemsData'=>$getNameData,'batchDetails'=>$batch_no];
        return response()->json(['success'=>'Medicine data found','data'=>$data],200);
    }



    public function billingUpdateDatas(Request $request)
{

    try {
        $billing_id = $request->billing_id;

        // Set old billing items to inactive
        BillingItem::where('billing_id', $billing_id)->update(['status' => 0]);

        // Update or insert billing items
        $item_idd = [];
        foreach ($request->editID as $key => $item_id) {
            array_push($item_idd, $item_id);

            if (empty($item_id)) {  
                // Insert new record
                $billingItem = new BillingItem();
                $billingItem->billing_id = $billing_id;
                $billingItem->category_id = $request->category[$key];
                $billingItem->name_id = $request->name[$key];
                $billingItem->batch_no = $request->batchNo[$key];
                $billingItem->expiry = $request->expiry[$key];
                $billingItem->qty = $request->qty[$key];
                $billingItem->sales_price = $request->salesPrice[$key];
                $billingItem->tax_per = $request->taxPer[$key];
                $billingItem->tax_amount = $request->taxAmount[$key] ?? null;
                $billingItem->amount = $request->amount[$key];
                $billingItem->status = 1; // Active record
                $billingItem->save();
            } else {  
                // Update existing record
                BillingItem::where('id', $item_id)
                    ->where('billing_id', $billing_id)
                    ->update([
                        'category_id' => $request->category[$key],
                        'name_id' => $request->name[$key],
                        'batch_no' => $request->batchNo[$key],
                        'expiry' => $request->expiry[$key],
                        'qty' => $request->qty[$key],
                        'sales_price' => $request->salesPrice[$key],
                        'tax_per' => $request->taxPer[$key],
                        'tax_amount' => $request->taxAmount[$key],
                        'amount' => $request->amount[$key],
                        'status' => 1
                    ]);
            }
        }

        // Update Billing Information
        Billing::where('id', $billing_id)->update([
            'bill_no' => $request->billNo,
            'patient_id' => $request->patientID,
            'res_doctor_id' => $request->resDoctor,
            'out_doctor_name' => $request->outDoctor,
            'naration' => $request->notes,
            'total_amount' => $request->totalAmount,
            'discount_per' => $request->discountPer,
            'discount_amount' => $request->totalDiscountAmount,
            'taxes' => $request->totalTaxAmount,
            'net_amount' => $request->totalNetAmount,
            'payment_mode' => $request->paymentMode,
            'paid_amount' => $request->payAmount,
            'due_amount' => $request->dueAmount
        ]);

        // Insert payment details
        $billingPayments = new BillingPayment();
        $billingPayments->billing_id = $billing_id;
        $billingPayments->payment_mode_id = $request->paymentMode;
        $billingPayments->amount = $request->payAmount;

        if (!$billingPayments->save()) {
            throw new \Exception("Failed to insert billing payment record");
        }

        // Remove inactive records
        BillingItem::where('billing_id', $billing_id)->where('status', 0)->delete();

        return response()->json(['success' => true, 'message' => 'Billing updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
    }
}
//     public function billingUpdateDatas(Request $request)
//     {
//         // dd($request->all());
        
//     try {
//         $billing_id = $request->billing_id;

//         // Set old billing items to inactive
//         BillingItem::where('billing_id', $billing_id)->update(['status' => 0]);

//         // Update or insert billing items
//         $item_idd = [];
//         foreach ($request->editID as $key => $item_id) {
//             array_push($item_idd,$item_id);
//           if (!isset($item_id)) { 
//                 // Insert new record
//                 $billingItem = new BillingItem();
//                 $billingItem->billing_id = $billing_id;
//                 $billingItem->category_id = $request->category[$key];
//                 $billingItem->name_id = $request->name[$key];
//                 $billingItem->batch_no = $request->batchNo[$key];
//                 $billingItem->expiry = $request->expiry[$key];
//                 $billingItem->qty = $request->qty[$key];
//                 $billingItem->sales_price = $request->salesPrice[$key];
//                 $billingItem->tax_per = $request->taxPer[$key];
//                 $billingItem->tax_amount = $request->taxAmount[$key] ?? null;
//                 $billingItem->amount = $request->amount[$key];
//                 $billingItem->status = 1; // Active record
//                 $billingItem->save();
//             } else {
//                 // Update existing record
//                 BillingItem::where('id', $item_id)
//                     ->where('billing_id', $billing_id)
//                     ->update([
//                         'category_id' => $request->category[$key],
//                         'name_id' => $request->name[$key],
//                         'batch_no' => $request->batchNo[$key],
//                         'expiry' => $request->expiry[$key],
//                         'qty' => $request->qty[$key],
//                         'sales_price' => $request->salesPrice[$key],
//                         'tax_per' => $request->taxPer[$key],
//                         'tax_amount' => $request->taxAmount[$key],
//                         'amount' => $request->amount[$key],
//                         'status' => 1
//                     ]);
//             }
//         }
// // dd($item_idd);
//         // Update Billing Information
//         Billing::where('id', $billing_id)->update([
//             'bill_no' => $request->billNo,
//             'patient_id' => $request->patientID,
//             'res_doctor_id' => $request->resDoctor,
//             'out_doctor_name' => $request->outDoctor,
//             'naration' => $request->notes,
//             'total_amount' => $request->totalAmount,
//             'discount_per' => $request->discountPer,
//             'discount_amount' => $request->totalDiscountAmount,
//             'taxes' => $request->totalTaxAmount,
//             'net_amount' => $request->totalNetAmount,
//             'payment_mode' => $request->paymentMode,
//             'paid_amount' => $request->payAmount,
//             'due_amount' => $request->dueAmount
//         ]);
//         $billingPayments = new BillingPayment();
//             $billingPayments->billing_id = $billing_id;
//             $billingPayments->payment_mode_id = $request->paymentMode;
//             $billingPayments->amount = $request->payAmount;
//             if (!$billingPayments->save()) {
//                     throw new \Exception("Failed to insert billing payment record");
//                 }
//         // Remove inactive records
//         BillingItem::where('billing_id', $billing_id)->where('status', 0)->delete();

//         return response()->json(['success' => true, 'message' => 'Billing updated successfully']);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
//     }
// }

    }
