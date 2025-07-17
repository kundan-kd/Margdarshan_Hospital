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
use App\Models\PaymentBill;
use App\Models\PaymentMode;
use App\Models\PurchaseItem;
use App\Models\PaymentReceived;
use App\Models\SaleReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->addColumn('created_at',function($row){
                $date = new \DateTime($row->created_at);
                $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                return $date->format('d-m-Y h:i A');
            })
            ->addColumn('patient_id',function($row){
                return $row->patientData->patient_id ?? 'NA';
            })
            ->addColumn('patient',function($row){
                return $row->patientData->name ??'Cash';
            })
            ->addColumn('bill_no',function($row){
                return $row->bill_no;
            })
            ->addColumn('net_amount',function($row){
                return $row->net_amount ?? 0;
            })
            ->addColumn('paid_amount',function($row){
                return $row->paid_amount ?? 0;
            })
            ->addColumn('action',function($row){
                $viewSaleReturn = $row->return_amount <= 0 ?'d-none':'';
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" title="View Purchase Details">
                        <iconify-icon icon="iconamoon:eye-light" onclick="purchaseDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Sale Return">
                         <iconify-icon icon="lucide:edit" onclick="billingEdit('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" title="View Medicine Bill">
                            <iconify-icon icon="mdi:file-download-outline" onclick="printMedicineBill(' . $row->id . ')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center '.$viewSaleReturn.'" title="View Sale Return">
                            <iconify-icon icon="lucide:edit" onclick="saleReturnView(' . $row->id . ')"></iconify-icon>
                        </a>
                        <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="purchaseDelete('.$row->id.')"></iconify-icon>
                        </a>-->';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
     public function billingAdd(){
        $categories_data = MedicineCategory::where('status',1)->get();
        $categories = [];
        foreach($categories_data as $cat ){
            $isCategoryMed = Medicine::where('category_id',$cat->id)->get(['stock_in','stock_out']);
            // dd($isCategoryMed[0]->stock_in,$isCategoryMed[0]->stock_out);
            if(sizeOf($isCategoryMed)>0){
                $total = $isCategoryMed[0]->stock_in -  $isCategoryMed[0]->stock_out;
                if($total > 0){
                    $categories[] = [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            }
            }
        }
        //  dd($medCategory);
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
            // Insert payment bill details
            if($request->totalNetAmount > 0){
            $payment_received = new PaymentBill();
            $payment_received->patient_id = $request->patientID;
            $payment_received->type = 'Billing';
            $payment_received->type_id = $billings->id;
            $payment_received->amount_for = 'Medicine Billing';
            $payment_received->title = 'Medicine Bill Amount';
            $payment_received->amount = $request->totalNetAmount;
            $payment_received->payment_mode = $request->paymentMode;
            $payment_received->save();
            }
            // Insert payment received details
            if($request->payAmount > 0){
            $payment_received = new PaymentReceived();
            $payment_received->patient_id = $request->patientID;
            $payment_received->type = 'Billing';
            $payment_received->type_id = $billings->id;
            $payment_received->amount_for = 'Medicine Billing';
            $payment_received->title = 'Medicine Billing Amount';
            $payment_received->amount = $request->payAmount;
            $payment_received->payment_mode = $request->paymentMode;
            $payment_received->save();
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
                $oldPurchaseItemStockOut = PurchaseItem::where('id', $request->batchNo[$index])->get(['stock_out']);
                PurchaseItem::where('id',$request->batchNo[$index])->update([
                    'stock_out' => $request->qty[$index] + $oldPurchaseItemStockOut[0]->stock_out
                ]);
                $oldMedicineStockOut = Medicine::where('id', $request->name[$index])->get(['stock_out']);
                medicine::where('id',$request->name[$index])->update([
                    'stock_out' => $request->qty[$index] + $oldMedicineStockOut[0]->stock_out
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
    public function getBillingData(Request $request){
        $getData = Billing::where('id',$request->id)->get();
        return response()->json(['success'=>'Billing data fetched','data'=>$getData],200);
    }
    public function billingSaleReturnView($id){
        $billings = Billing::where('id',$id)->get();
        $billingItems = BillingItem::where('billing_id',$id)->get();
        $categories = MedicineCategory::where('status',1)->get();
        $doctors = User::where('usertype_id',2)->where('status',1)->get();
        $patients = Patient::where('status',1)->get(['id','patient_id','name']);
        $bloodtypes = BloodType::where('status',1)->get();
        $paymentmodes = PaymentMode::where('status',1)->get();
        return view('backend.admin.modules.pharmacy.billing-sale-return',compact('billings','billingItems','categories','doctors','patients','bloodtypes','paymentmodes'));
    }
// public function billingUpdateDatas(Request $request)
// {
//     try {
//         $billing_id = $request->billing_id;

//         foreach ($request->editID as $key => $item_id) {
//             $newQty = $request->qty[$key];
//             $returnAmount = $request->return_amount[$key];

//             // Fetch old billing item
//             $billingItem = BillingItem::find($item_id);
//             if (!$billingItem) continue;

//             $oldQty = $billingItem->qty;

//             // Update BillingItem with new quantity and return amount
//             $billingItem->qty = $newQty;
//             $billingItem->return_amount = $returnAmount; // Ensure this column exists
//             $billingItem->amount -= $returnAmount; // Reduce total amount if applicable
//             $billingItem->save();

//             // Update stock in Medicine and PurchaseItem
//             $medicine = Medicine::find($billingItem->name_id);
//             $purchaseItem = PurchaseItem::find($billingItem->batch_no);

//             if ($medicine && $purchaseItem) {
//                 $diffQty = $oldQty - $newQty; // Return quantity
//                 if ($diffQty > 0) {
//                     $medicine->stock_out = max(0, $medicine->stock_out - $diffQty);
//                     $purchaseItem->stock_out = max(0, $purchaseItem->stock_out - $diffQty);
//                     $medicine->save();
//                     $purchaseItem->save();
//                 }
//             }
//         }

//         // Recalculate billing values
//         $updatedItems = BillingItem::where('billing_id', $billing_id)->get();

//         $totalAmount = $updatedItems->sum('amount');
//         $totalReturn = $updatedItems->sum('return_amount');
//         $netAmount = $totalAmount - $totalReturn;

//         // Update Billing record
//         Billing::where('id', $billing_id)->update([
//             'total_amount' => $totalAmount,
//             'net_amount' => $netAmount,
//             'due_amount' => $netAmount, // Adjust according to payments if needed
//         ]);

//         return response()->json(['success' => true, 'message' => 'Sale return updated successfully']);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Something went wrong during sale return update',
//             'error' => $e->getMessage()
//         ]);
//     }
// }

    public function billingUpdateDatas(Request $request){
        // dd($request->all());
    try {
        $billing_id = $request->billing_id;
        foreach ($request->editID as $key => $item_id) {
            $prevBillingItem = BillingItem::where('id', $item_id)->where('billing_id', $billing_id)->first('return_qty','return_amount');
            BillingItem::where('id', $item_id)->where('billing_id', $billing_id)->update([
                    'return_qty' => $prevBillingItem->return_qty + $request->returnQty[$key],
                    'return_amount' => $prevBillingItem->return_amount + $request->return_amount[$key] ?? 0
                ]);
            $prevPurchaseItem = PurchaseItem::where('id',$request->batchID[$key])->first('return_qty');
            PurchaseItem::where('id',$request->batchID[$key])->update([
                'return_qty' => $prevPurchaseItem->return_qty + $request->returnQty[$key]
            ]);
            $prevMedicine = Medicine::where('id',$request->name[$key])->first('return_qty');
            Medicine::where('id',$request->name[$key])->update([
                'return_qty' => $prevMedicine->return_qty + $request->returnQty[$key]
            ]);  
        }
        $prevBilling = Billing::where('id', $billing_id)->first('return_amount');
        Billing::where('id', $billing_id)->update([
            'return_amount' => $prevBilling->return_amount + $request->total_return_amount ?? 0,
        ]);

            $sale_returns = new SaleReturn();
            $sale_returns->type = "Billing"; 
            $sale_returns->type_id = $billing_id; 
            $sale_returns->return_amount = $request->total_return_amount ?? 0; 
            $sale_returns->created_by = Auth::id();
            $sale_returns->save();

        return response()->json(['success' => 'Sale Return done successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
    }
}
    public function billingViewIndex($id){
        $billings = Billing::where('id',$id)->get();

        if($billings[0]->patient_id > 0){
            $patientId = $billings[0]->patient_id;
            $patient_data = Patient::where('id',$patientId)->get(['name','patient_id']);
            $patient_id = $patient_data[0]->patient_id;
            $patientName = $patient_data[0]->name;
        }else{
            $patient_id = '00000';
            $patientName = 'Cash';
        }
        $billingItems = BillingItem::with('categoryData','medicineNameData')->where('billing_id',$id)->get();
        return view('backend.admin.modules.pharmacy.billing-view',compact('billings','billingItems','patient_id','patientName'));
    }


    }
