<?php

namespace App\Http\Controllers\backend\admin\pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\PaymentReceived;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index(){
        return view('backend.admin.modules.pharmacy.purchase');
    }
    public function purchaseAdd(){
        $categories = MedicineCategory::where('status',1)->get();
        $vendors = Vendor::where('status',1)->get();
        // $medicines = Medicine::with('categoryData')->select('category_id')->distinct()->get(); // to fetch distinct categories of medicines category field required
        return view('backend.admin.modules.pharmacy.purchase-add',compact('categories','vendors'));
    }
    public function purchaseView(Request $request){
        if($request->ajax()){
            $purchase = Purchase::get();
            return DataTables::of($purchase)
            ->addColumn('created_at',function($row){
                return $row->created_at;
            })
            ->addColumn('bill_no',function($row){
                return $row->bill_no;
            })
            ->addColumn('net_amount',function($row){
                return $row->net_amount;
            })
            ->addColumn('discount',function($row){
                return $row->total_discount;
            })
            ->addColumn('total',function($row){
                return $row->total_amount;
            })
            ->addColumn('paid_amount',function($row){
                return $row->paid_amount;
            })
            ->addColumn('due',function($row){
                return $row->due;
            })
            ->addColumn('naration',function($row){
                return $row->naration;
            })
            ->addColumn('action',function($row){

                // $encryptedId = Crypt::encryptString($row->id);
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="iconamoon:eye-light" onclick="purchaseDetails('.$row->id.')"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="lucide:edit" onclick="purchaseEdit('.$row->id.')"></iconify-icon>
                         </a>
                         <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                         <iconify-icon icon="mingcute:delete-2-line" onclick="purchaseDelete('.$row->id.')"></iconify-icon>
                         </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function purchaseAddDatas(Request $request){
    $validator = Validator::make($request->all(), [
        'billNo' => 'required',
        'vendorID' => 'required',
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
        'payAmount' => 'nullable',
    ]);

    // Return validation errors if validation fails
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
        $purchase->total_amount = $request->totalAmount;
        $purchase->total_discount_per = $request->totalDiscountPer;
        $purchase->total_discount = $request->totalDiscount;
        $purchase->total_tax = $request->totalTaxAmount;
        $purchase->net_amount = $request->totalNetAmount;
        $purchase->payment_mode = $request->paymentMode;
        $purchase->paid_amount = $request->payAmount;
        $purchase->due = $request->dueAmount;
        $purchase->naration = $request->naration;

        if (!$purchase->save()) {
            throw new \Exception("Failed to insert purchase record");
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
                    // Insert new record
                $purchaseItem = new PurchaseItem();
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
                    $purchaseItem->save();

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
            $prev_paidAmount = Purchase::where('id',$purchase_id)->get(['paid_amount']);
            // dd($request->dueAmount,$request->payAmount,$prev_paidAmount[0]->paid_amount);
            Purchase::where('id', $purchase_id)->update([
                'bill_no' => $request->billNo,
                'vendor_id' => $request->vendorID,
                'naration' => $request->naration,
                'total_amount' => $request->totalAmount,
                'total_discount_per' => $request->totalDiscountPer,
                'total_discount' => $request->totalDiscount,
                'total_tax' => $request->totalTaxAmount,
                'net_amount' => $request->totalNetAmount,
                'payment_mode' => $request->paymentMode,
                'paid_amount' => $request->payAmount + $prev_paidAmount[0]->paid_amount,
                'due' => $request->dueAmount
            ]);
            // if($request->payAmount > 0){
            // $payment_received = new PaymentReceived();
            // $payment_received->type = 'Purchase';
            // $payment_received->type_id = $purchase_id;
            // $payment_received->amount = $request->payAmount;
            // $payment_received->payment_mode = $request->paymentMode;
            // $payment_received->save();
            // }
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
     $getData = MedicineCategory::get();
     return response()->json(['success'=>'Category data found','data'=>$getData],200);   
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
