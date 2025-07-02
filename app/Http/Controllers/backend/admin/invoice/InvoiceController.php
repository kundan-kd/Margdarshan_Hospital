<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\Timeline;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function generateEmergencyBills($id){
        $patient_id = $id;
           // Get the most recent 'Bed Charge' payment bill for a specific patient
        $previous_payment_bill = PaymentBill::where('patient_id', $id)->where('amount_for', 'Bed Charge')->latest('id')->first();
        $pre_bed_amount = 0;
        if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
            $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
            $created_at = new DateTime($previous_payment_bill->created_at);
            $updated_at = new DateTime(); // Current date and time
            $interval = $created_at->diff($updated_at);
            $occupied_days = max((int)$interval->days, 1); // Ensure at least 1 day
            $pre_bed_amount = $bed_amount * $occupied_days;
        }
        $payment_bills = PaymentBill::where('patient_id',$id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->sum('discount_amount');
        return view('backend.admin.modules.invoice.discharge-bill',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','pre_bed_amount'));
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
        $patient_id = $id;
        $payment_bills = PaymentBill::where('patient_id',$id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->sum('discount_amount');
        $invoice_data = Invoice::where('patient_id',$id)->get(['id','created_at']);
        return view('backend.admin.modules.invoice.discharge-bill-invoice',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','invoice_data'));
    }
    public function getPatientDischarge(Request $request){
            // Get the most recent 'Bed Charge' payment bill for a specific patient
          $previous_payment_bill = PaymentBill::where('patient_id', $request->id)->where('amount_for', 'Bed Charge')->latest('id')->first();
           if ($previous_payment_bill) {
                $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
                $created_at = new DateTime($previous_payment_bill->created_at);
                $updated_at = new DateTime(); // Current date and time
                $interval = $created_at->diff($updated_at);
                $occupied_days = max((int)$interval->days, 1); // Ensure at least 1 day
                $pre_bed_amount = $bed_amount * $occupied_days;
                PaymentBill::where('id',$previous_payment_bill->id)->update([
                    'amount' => $pre_bed_amount
                ]);
            } // amount add to previous bed type for billing
            $type = Patient::where('id',$request->id)->pluck('type');
            $update = Patient::where('id',$request->id)->update([
            'current_status' =>'Discharged'
            ]);
            if($update){
                $timelines = new Timeline();
                $timelines->type = $type[0];
                $timelines->patient_id = $request->id;
                $timelines->title = "Discharged";
                $timelines->desc = "Patient Discharged from ".$type[0];
                $timelines->created_by = Auth::id();
                $timelines->save();
                return response()->json(['success'=>'Successfully discharged from '.$type[0]],200);
            }
                return response()->json(['success'=>'Discharge done successfully'],200);
    }
    public function invoiceDataSubmit(Request $request){
        $type = Patient::where('id',$request->id)->pluck('type');
        $invoices = new Invoice();
        $invoices->patient_id = $request->id;
        $invoices->type = $type[0];
        $invoices->amount = $request->total_amount;
        $invoices->discount = $request->discount_amount;
        $invoices->paid_amount = $request->paid_amount;
        $invoices->status = "Discharge";
        $invoices->created_by = Auth::id(); // This gets the logged-in user's ID
        $invoices->save();
    }
    public function medicineBillPrint($id){
        $patient_id = $id;
        $billings = Billing::where('id',$id)->get();
        $billing_items = BillingItem::where('billing_id',$id)->get();
        return view('backend.admin.modules.invoice.medicine-bill-invoice',compact('patient_id','billings','billing_items'));
    }
}
