<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchasePayment;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.purchase');
    }
    public function purchaseAdd(){
        $categories = MedicineCategory::where('status',1)->get();
        $vendors = Vendor::where('status',1)->get();
        return view('backend.admin.modules.pharmacy.purchase-add',compact('categories','vendors'));
    }
    public function purchaseView(Request $request){
        if($request->ajax()){
            $purchase = Purchase::get();
            return DataTables::of($purchase)
            ->addColumn('created_at',function($row){
                // $date = new \DateTime($row->created_at);
                // $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                return date('d-m-Y', strtotime($row->purchase_date));
            })
            ->addColumn('vendor',function($row){
                return $row->vendorData->name;
            })
            ->addColumn('bill_no',function($row){
                return $row->bill_no;
            })
            ->addColumn('net_amount',function($row){
                return $row->net_amount;
            })
            ->addColumn('paid_amount',function($row){
                return $row->paid_amount ?? 0;
            })
            ->addColumn('due_amount',function($row){
                return round($row->due ?? 0,2);
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="iconamoon:eye-light" onclick="purchaseDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="purchaseEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <!--<a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="purchaseDelete('.$row->id.')"></iconify-icon>
                         </a>-->';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function purchaseAddDatas(Request $request){
        $validator = Validator::make($request->all(), [
            'billNo' => 'required',
            'vendorID' => 'required',
            'purchase_date' => 'required',
            'category' => 'required|array',
            'name' => 'required|array',
            'batchNo' => 'required|array',
            'expiry' => 'required|array',
            'mrp' => 'required|array',
            'salesPrice' => 'required|array',
            'tax' => 'required|array',
            'qty' => 'required|array',
            'purchaseRate' => 'required|array',
            'amount' => 'required|array',
            'naration'=>'nullable',
            'totalAmount' => 'required',
            'totalDiscount' => 'nullable',
            'totalTaxAmount' => 'required',
            'totalNetAmount' => 'required',
            'paymentMode' => 'nullable',
            'txn' => 'nullable',
            'payAmount' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(['error_validation' => $validator->errors()->all()], 200);
        }
        // Start a database transaction
        DB::beginTransaction();
        try {
            // Insert purchase details into purchases table
            $purchase = new Purchase();
            $purchase->bill_no = $request->billNo;
            $purchase->vendor_id = $request->vendorID;
            $purchase->purchase_date = Carbon::createFromFormat('d-m-Y', $request->purchase_date)->format('Y-m-d');
            $purchase->total_amount = $request->totalAmount;
            $purchase->total_discount_per = $request->totalDiscountPer;
            $purchase->total_discount = $request->totalDiscount;
            $purchase->total_tax = $request->totalTaxAmount;
            $purchase->net_amount = $request->totalNetAmount;
            $purchase->paid_amount = $request->payAmount;
            $purchase->due = $request->dueAmount;
            $purchase->naration = $request->naration;
            if ($purchase->save()) {
                if($request->payAmount > 0){
                    $payment_received = new PurchasePayment();
                    $payment_received->type = 'Purchase';
                    $payment_received->type_id =$purchase->id;
                    $payment_received->amount = $request->payAmount;
                    $payment_received->payment_mode = $request->paymentMode;
                    $payment_received->txn_no = $request->txn;
                    $payment_received->save();
                }
            }
            // Insert purchase items into purchase_items table
            foreach ($request->category as $index => $category) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->category_id = $category;
                $purchaseItem->name_id = $request->name[$index];
                $purchaseItem->batch_no = $request->batchNo[$index];
                $purchaseItem->expiry = $request->expiry[$index];
                $purchaseItem->mrp = $request->mrp[$index];
                $purchaseItem->sales_price = $request->salesPrice[$index];
                $purchaseItem->tax = $request->tax[$index];
                $purchaseItem->qty = $request->qty[$index];
                $purchaseItem->purchase_rate = $request->purchaseRate[$index];
                $purchaseItem->amount = $request->amount[$index];
                $purchaseItem->tax_amount = ($request->amount[$index] * $request->tax[$index])/100;; 
                //update stock_in for the medicine starts
                $oldStocks = Medicine::where('id', $request->name[$index])->get(['stock_in']);
                $oldMedicineStock = $oldStocks[0]->stock_in ?? 0;
                Medicine::where('id', $request->name[$index])->update([
                    'stock_in' => $oldMedicineStock + $request->qty[$index]
                ]);
                //update stock_in for the medicine ends
                if (!$purchaseItem->save()) {
                    throw new \Exception("Failed to insert purchase item record");
                }
            }
            // Commit transaction if all operations succeed
            DB::commit();
            return response()->json(['success' => 'Purchase and purchase items added successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function purchaseEditPage($id){
        $categories = MedicineCategory::where('status',1)->get();
        $medicines = Medicine::get(); // to fetch distinct categories of medicines category_id field required
        $purchase = Purchase::where('id',$id)->get();
        $vendors = Vendor::where('status',1)->get();
        $purchaseItems = PurchaseItem::with('categoryData','medicineNameData')->where('purchase_id',$id)->get(); // Fetching purchase items with their category_id and medicine name_id data
        return view('backend.admin.modules.pharmacy.purchase-edit',compact('categories','medicines','purchase','vendors','purchaseItems'));
    }
    public function purchaseUpdateDatas(Request $request){
        try {
            $purchase_id = $request->purchase_id;
            PurchaseItem::where('purchase_id', $purchase_id)
                        ->update([
                            'status' => 0
                        ]);
            // Update or insert purchase items
            foreach ($request->id as $key => $item_id) {
                if (is_null($item_id)) {
                    $purchaseItem = new PurchaseItem();  // Insert new record
                    $purchaseItem->purchase_id = $purchase_id;
                    $purchaseItem->category_id = $request->category[$key];
                    $purchaseItem->name_id = $request->name[$key];
                    $purchaseItem->batch_no = $request->batchNo[$key];
                    $purchaseItem->expiry = $request->expiry[$key];
                    $purchaseItem->mrp = $request->mrp[$key];
                    $purchaseItem->sales_price = $request->salesPrice[$key];
                    $purchaseItem->tax = $request->tax[$key];
                    $purchaseItem->qty = $request->qty[$key];
                    $purchaseItem->purchase_rate = $request->purchaseRate[$key];
                    $purchaseItem->amount = $request->amount[$key];
                    $purchaseItem->tax_amount = ($request->amount[$key] * $request->tax[$key])/100;
                        if ($purchaseItem->save()) {
                            $oldStock = Medicine::where('id', $request->name[$key])->pluck('stock_in')->first();
                            Medicine::where('id', $request->name[$key])->update([
                                'stock_in' => $oldStock + $request->qty[$key]
                            ]);
                        }
                } else {
                    $old_purchase_itm_qty = PurchaseItem::where('id', $item_id)->value('qty');
                    // Update existing record
                    PurchaseItem::where('id', $item_id)
                        ->where('purchase_id', $purchase_id)
                        ->update([
                            'category_id' => $request->category[$key],
                            'name_id' => $request->name[$key],
                            'batch_no' => $request->batchNo[$key],
                            'expiry' => $request->expiry[$key],
                            'mrp' => $request->mrp[$key],
                            'sales_price' => $request->salesPrice[$key],
                            'tax' => $request->tax[$key],
                            'qty' => $request->qty[$key],
                            'purchase_rate' => $request->purchaseRate[$key],
                            'amount' => $request->amount[$key],
                            'tax_amount' => ($request->amount[$key] * $request->tax[$key])/100,
                            'status' =>1
                        ]);
                //update stock_in for the medicine starts
                $oldStocks = Medicine::where('id', $request->name[$key])->get(['stock_in']);
                $oldMedicineStock = $oldStocks[0]->stock_in ?? 0;
                $curr_purchase_itm_qty = PurchaseItem::where('id', $item_id)->value('qty');
                if($old_purchase_itm_qty > $curr_purchase_itm_qty){
                    $subQty = $old_purchase_itm_qty - $curr_purchase_itm_qty;
                    Medicine::where('id', $request->name[$key])->update([
                    'stock_in' => $oldMedicineStock - $subQty
                    ]);
                }else{
                    $addQty = $curr_purchase_itm_qty - $old_purchase_itm_qty;
                    Medicine::where('id', $request->name[$key])->update([
                    'stock_in' => $oldMedicineStock + $addQty
                    ]);
                }        
                //update stock_in for the medicine ends
                }
            }
            // Update the purchase record
            $prev_paidAmount = Purchase::where('id',$purchase_id)->pluck('paid_amount')->first();
            Purchase::where('id', $purchase_id)->update([
                'bill_no' => $request->billNo,
                'vendor_id' => $request->vendorID,
                'naration' => $request->naration,
                'total_amount' => $request->totalAmount,
                'total_discount_per' => $request->totalDiscountPer,
                'total_discount' => $request->totalDiscount,
                'total_tax' => $request->totalTaxAmount,
                'net_amount' => $request->totalNetAmount,
                'paid_amount' => $request->payAmount + $prev_paidAmount,
                'due' => $request->totalNetAmount - ($prev_paidAmount + $request->payAmount)
            ]);
            if($request->payAmount > 0){
                $payment_received = new PurchasePayment();
                $payment_received->type = 'Purchase';
                $payment_received->type_id = $purchase_id;
                $payment_received->amount = $request->payAmount;
                $payment_received->payment_mode = $request->paymentMode;
                $payment_received->txn_no = $request->txn;
                $payment_received->save();
            }
            PurchaseItem::where('purchase_id',$request->purchase_id)->where('status',0)->delete();
            return response()->json(['success' => true, 'message' => 'Purchase updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }
    function deletePurchasedetails(Request $request){
        Purchase::where('id',$request->id)->delete();
        return response()->json(['success'=>'Purchase deleted successfully'],200);
    }
    function getPurchaseNamesSelectEdit(Request $request){
        $getData = Medicine::where('category_id',$request->id)->get();
        $getNameData = PurchaseItem::where('id',$request->purchaseID)->get(['name_id']);
         $data = ['medicines'=>$getData,'nameData'=>$getNameData];
         return response()->json(['success'=>'Medicine data found','data'=>$data],200);
    }
    
    public function getCategoryDatas(){
        $getData = MedicineCategory::where('status',1)->get(['id','name']);
        $categoryList = [];
        foreach($getData as $cate){
            $billMedicine = [];
            $medicines = $cate->medicineData;
            foreach($medicines as $med){
                $chk = PurchaseItem::where('name_id',$med->id)->get(['id','batch_no','expiry','qty','return_qty','stock_out','sales_price','tax']);
                if(sizeOf($chk) > 0){
                    $billMedicine[] =[
                        'id' => $med->id,
                        'name' => $med->name,
                        'purchase' => $chk
                    ];
                }
            }
            
            $categoryList[]=[
                'id' => $cate->id,
                'name' => $cate->name,
                'medicine' => $cate->medicineData,
                'billMedicine' => $billMedicine
            ];
        }
        
        return response()->json(['success'=>'Category data found','data'=>$getData,'categoryList' => $categoryList],200);   
    }
    
    public function pruchaseViewIndex($id){
        // dd($request);
        $purchases = Purchase::with('vendorData')->where('id',$id)->get();
        $purchaseItems = PurchaseItem::with('categoryData','medicineNameData')->where('purchase_id',$id)->get();
        // dd($purchases,$purchaseItems);
        return view('backend.admin.modules.pharmacy.purchase-view',compact('purchases','purchaseItems'));
    }
    public function getPurchaseData(Request $request){
        $getData = Purchase::where('id',$request->id)->get();
        return response()->json(['success'=>'Purchase data fetched','data'=>$getData],200);
    }
}
