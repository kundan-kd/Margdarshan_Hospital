<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\DischargeSummary;
use App\Models\Invoice;
use App\Models\LabInvestigation;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\Timeline;
use App\Models\Visit;
use App\Models\Vital;
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
        $occupied_days = 0;
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
        $notes = Invoice::where('patient_id',$id)->get(['notes']);
        return view('backend.admin.modules.invoice.discharge-bill',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','occupied_days','pre_bed_amount','notes'));
    }
    public function payBillAmount(Request $request){
          $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'pmode' => 'required',
            'txn' => 'nullable'
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
        $payment_received->txn_no = $request->txn;
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
            'current_status' =>'Discharged',
            'discharge_date' =>now()
            ]);
            $previous_bed_data = Bed::where('occupied_by_patient_id',$request->id)->get();
             Bed::where('id', $previous_bed_data[0]->id)->update([
                'previous_occupied_patient_id' => $previous_bed_data[0]->occupied_by_patient_id,
                'previous_occupied_date' => $previous_bed_data[0]->occupied_date,
                'occupied_by_patient_id' => null,
                'occupied_date' => null,
                'current_status' =>'vacant'
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
        $invoices->notes = $request->notes;
        $invoices->status = "Discharge";
        $invoices->created_by = Auth::id(); // This gets the logged-in user's ID
        $invoices->save();
    }
    public function medicineBillPrint($id){
        $billings = Billing::where('id',$id)->get();
        $patient_id = $billings[0]->patient_id;
        $billing_items = BillingItem::where('billing_id',$id)->get();
        return view('backend.admin.modules.invoice.medicine-bill-invoice',compact('patient_id','billings','billing_items'));
    }
    public function appointmentBillPrint($id){
        $appointments = Appointment::where('id',$id)->get();
        return view('backend.admin.modules.invoice.appointment-invoice',compact('appointments'));
    }
    public function admissionFormPrint($id){
        $patients = Patient::where('id',$id)->get();
        return view('backend.admin.modules.invoice.admission-form',compact('patients'));
    }
     public function dischargeSummary($id){
        $medications = Medication::where('patient_id',$id)->where('remarks','Discharge')->get();
        // Fetch today's vitals for the patient
        $vitals = Vital::where('patient_id', $id)
            ->whereDate('created_at', now()->toDateString())
            ->get();
        return view('backend.admin.modules.invoice.discharge-summary',compact('id','medications','vitals'));
    }
    public function dischargeFormPrint($id){
        $patients = Patient::where('id',$id)->get();
        $dischargeSummary = DischargeSummary::where('patient_id',$id)->get();
        return view('backend.admin.modules.invoice.discharge-form',compact('patients','dischargeSummary'));
    }
    public function dischargeSummarySubmit(Request $request){
        $validated = $request->validate([
            'patient_id' => 'required',
            'discharge_type' => 'required',
            'final_diagnosis' => 'required',
        ]);
        $summary = new DischargeSummary();
        $summary->patient_id = $validated['patient_id'];
        $summary->final_diagnosis = $validated['final_diagnosis'];
        if($summary->save()){
            Patient::where('id',$summary->patient_id)->update([
                'discharge_form_generated' => 1,
                'discharge_type' => $validated['discharge_type']
            ]);
            return response()->json(['success'=>'Discharge summary submited successfully'],200);
        }else{
            return response()->json(['error_success'=>'Discharge summary not submited']);
        }
    }
    public function summaryRepoet($id){
        $patients = Patient::where('id',$id)->get();
        $visitsData = Visit::with('doctorData')->where('patient_id',$id)->where('type','OPD')->get();
        $medicationData = Medication::with('medicineNameData')->where('patient_id',$id)->get();
        $vitalsData = Vital::where('patient_id',$patients[0]->id)->get();
        $labInvestigationData = LabInvestigation::with('testNameData')->where('patient_id',$id)->get();
        return view('backend.admin.modules.invoice.summary-report',compact('patients','visitsData','medicationData','vitalsData','labInvestigationData'));
    }
    public function advancePaymentPage($id){
        $patients = Patient::where('id',$id)->get();
        $advanve_amount = PaymentReceived::where('patient_id',$id)->where('amount_for','Advance')->get();
        return view('backend.admin.modules.invoice.advance-payment',compact('patients','advanve_amount'));
    }
     public function billPrint($id){
        $patient_id = $id;
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
        return view('backend.admin.modules.invoice.discharge-bill-print',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','pre_bed_amount'));
    }
}
