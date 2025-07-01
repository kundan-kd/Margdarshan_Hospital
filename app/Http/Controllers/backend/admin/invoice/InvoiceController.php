<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateEmergencyBills($id){
        $patient_id = $id;
        $payment_bills = PaymentBill::where('patient_id',$id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->sum('amount');
        return view('backend.admin.modules.invoice.discharge-bill',compact('payment_bills','patient_id','total_amount','received_amount'));
    }
    public function payBillAmount(Request $request){
        
    }
}
