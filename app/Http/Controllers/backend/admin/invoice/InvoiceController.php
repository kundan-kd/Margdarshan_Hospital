<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function generateEmergencyBills($id){
        $patient_id = $id;
        $payment_bills = PaymentBill::where('patient_id',$id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->sum('discount_amount');
        return view('backend.admin.modules.invoice.discharge-bill',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount'));
    }
    public function payBillAmount(Request $request){
          $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'pmode' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=>$validator->errors()->all()],200);
        }
        $payment_received = new PaymentReceived(); 
        $payment_received->patient_id = $request->patientId;
        $payment_received->type = $request->type;
        $payment_received->amount_for = "Discharge";
        $payment_received->amount = $request->amount;
        $payment_received->discount_amount = $request->discount_amount;
        $payment_received->payment_mode = $request->pmode;
        if($payment_received->save()){
            return response()->json(['success'=>'Discharge amount submitted successfully'],200);
        }else{
            return response()->json(['error_success'=>'Discharge amount not submitted']);
        }
    }
    public function dischargeBillPrint($id){
        return view('backend.admin.modules.invoice.discharge-bill-invoice');
    }
}
