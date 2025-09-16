<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use App\Models\AdmitList;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Billing;
use App\Models\BillingItem;
use DateTimeZone;
use App\Models\DischargeSummary;
use App\Models\Invoice;
use App\Models\LabInvestigation;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PaymentBill;
use App\Models\PaymentReceived;
use App\Models\Timeline;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function generateDischargeBill($id,$admit_id){
        $patient_id = $id;
           // Get the most recent 'Bed Charge' payment bill for a specific patient
        $previous_payment_bill = PaymentBill::where('patient_id', $id)->where('admit_id',$admit_id)->where('amount_for', 'Bed Charge')->latest('id')->first();
        $occupied_days = 0;
        $pre_bed_amount = 0;
         if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
            $admitTime = new DateTime($previous_payment_bill->created_at);
            $dischargeTime = new DateTime();
            $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value

            // if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
            //     $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
               
            //     $admitTime = new DateTime($previous_payment_bill->created_at);
            //     $dischargeTime = new DateTime();
    
            //     // 2:00 PM cut-off
            //     $cutOffHour = 14;
            //     $days = 0;
    
            //     // Clone dates to avoid modifying originals
            //     $admitDate = clone $admitTime;
            //     $dischargeDate = clone $dischargeTime;
    
            //     // Only dates (set time to 00:00:00)
            //     $admitDate->setTime(0, 0, 0);
            //     $dischargeDate->setTime(0, 0, 0);
    
            //     // Difference in days (full calendar days)
            //     $intervalDays = $admitDate->diff($dischargeDate)->days;
    
            //     // Add intermediate full days (excluding first and last day)
            //     if ($intervalDays > 1) {
            //         $days += $intervalDays - 1;
            //     }
    
            //     // Admit day logic
            //     if ((int)$admitTime->format('H') < $cutOffHour) {
            //         $days += 1;
            //     }
    
            //     // Discharge day logic
            //     if ((int)$dischargeTime->format('H') < $cutOffHour) {
            //         $days += 1;
            //     }
    
            //     // Handle same-day admit/discharge
            //     if ($intervalDays === 0) {
            //         // If admitted and discharged same day, and either side meets the <2PM rule
            //         if ((int)$admitTime->format('H') < $cutOffHour || (int)$dischargeTime->format('H') < $cutOffHour) {
            //             $days = 1;
            //         } else {
            //             $days = 0;
            //         }
            //     }
    
            //     $occupied_days = $days;
            //     $pre_bed_amount = $bed_amount * $days;
            // }
        
        
            $checkIn  = Carbon::parse($admitTime);   // e.g. 2025-09-01 13:45:18
            $checkOut = Carbon::parse($dischargeTime); // e.g. 2025-09-03 14:45:18
            $cutoffHour = 14;
            $days = 0;
            // Case 1: If check-in before 2 PM, count first day
            if ($checkIn->hour < $cutoffHour) {
                $days++;
            }
            // Case 2: Count full 24-hour blocks between check-in and check-out
            $days += $checkIn->diffInDays($checkOut);
        
            // Case 3: If checkout is after 2 PM, add an extra day
            if ($checkOut->hour >= $cutoffHour) {
                $days++;
            }
            $occupied_days = round($days);
            $pre_bed_amount = $bed_amount * round($days);
            
            
            // dd($checkIn,$checkOut,$days,$occupied_days,$pre_bed_amount);
         }
        $payment_bills = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('discount_amount');
        $notes = Invoice::where('patient_id',$id)->where('admit_id',$admit_id)->get(['notes']);
        $admit_list = AdmitList::where('patient_id',$id)->where('admit_id',$admit_id)->get(['current_status','discharge_form_generated']);
        return view('backend.admin.modules.invoice.discharge-bill',compact('payment_bills','patient_id','admit_id','total_amount','received_amount','discount_amount','occupied_days','pre_bed_amount','notes','admit_list'));
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
        $admit_id = Patient::where('id',$request->patientId)->value('admit_id');
        $payment_received = new PaymentReceived(); 
        $payment_received->patient_id = $request->patientId;
        $payment_received->admit_id = $admit_id;
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
    public function dischargeBillPrint($id,$admit_id){
      
        $patient_id = $id;
        // $admit_id = Patient::where('id',$id)->value('admit_id');
        $payment_bills = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('discount_amount');
        $invoice_data = Invoice::where('patient_id',$id)->where('admit_id',$admit_id)->get(['id','discount','created_at']);
        return view('backend.admin.modules.invoice.discharge-bill-invoice',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','invoice_data'));
    }
    public function getPatientDischarge(Request $request){
        $latest_patient_log = PatientLog::where('patient_id',$request->id)->latest()->first();
        $type = Patient::where('id',$request->id)->pluck('type');
        $now = now();
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
            PaymentBIll::where('admit_id',$latest_patient_log->admit_id)->update([
                'status' => 'Discharged'
            ]); // for current patinet bill calculation
            PaymentReceived::where('admit_id',$latest_patient_log->admit_id)->update([
                'status' => 'Discharged'
            ]); // for current patinet bill calculation
            AdmitList::where('admit_id',$latest_patient_log->admit_id)->update([
                'type' => $type[0],
                'current_status' => 'Discharged',
                'discharge_date' => $now
            ]); // stored data to display patinet privious discharge record

            $update = Patient::where('id',$request->id)->update([
            'appointment_status' => 0,
            'current_status' =>'Discharged',
            'discharge_date' =>$now
            ]);
            
            $patient_logs = new PatientLog();
            $patient_logs->patient_id = $request->id;
            $patient_logs->admit_id = $latest_patient_log->admit_id;
            $patient_logs->type = $type[0];
            $patient_logs->bed_id = $latest_patient_log->bed_id;
            $patient_logs->doctor_id = $latest_patient_log->doctor_id;
            $patient_logs->reference_person = $latest_patient_log->reference_person;
            $patient_logs->current_status = "Discharged";
            $patient_logs->discharge_date = $now;
            $patient_logs->description = "Discharged from " . $type[0];
            $patient_logs->save();

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
                $timelines->admit_id = $latest_patient_log->admit_id;
                $timelines->title = "Discharged";
                $timelines->desc = "Patient Discharged from ".$type[0];
                $timelines->bed_id = $latest_patient_log->bed_id;
                $timelines->created_by = Auth::id();
                $timelines->save();
                return response()->json(['success'=>'Successfully discharged from '.$type[0]],200);
            }
                return response()->json(['success'=>'Discharge done successfully'],200);
    }
    public function invoiceDataSubmit(Request $request){
        $type = Patient::where('id',$request->id)->get(['type','admit_id']);
        $invoices = new Invoice();
        $invoices->patient_id = $request->id;
        $invoices->admit_id = $type[0]->admit_id;
        $invoices->type = $type[0]->type;
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
        $appointments = PatientLog::where('id',$id)->get();
        return view('backend.admin.modules.invoice.appointment-invoice',compact('appointments'));
    }
    public function admissionFormPrint($id){
        $patients = Patient::where('id',$id)->get();
        return view('backend.admin.modules.invoice.admission-form',compact('patients'));
    }
     public function dischargeSummary($id,$admit_id){
        $medications = Medication::where('patient_id',$id)->where('remarks','Discharge')->get();
        // Fetch today's vitals for the patient
        $vitals = Vital::where('patient_id', $id)
            ->whereDate('created_at', now()->toDateString())
            ->get();
        return view('backend.admin.modules.invoice.discharge-summary',compact('id','admit_id','medications','vitals'));
    }
    public function dischargeFormPrint($id,$admit_id){
        $patients = Patient::where('id',$id)->get();
        $dischargeSummary = DischargeSummary::where('patient_id',$id)->where('admit_id',$admit_id)->get();
        return view('backend.admin.modules.invoice.discharge-form',compact('patients','dischargeSummary'));
    }
    public function dischargeSummarySubmit(Request $request){
        $validated = $request->validate([
            'patient_id' => 'required',
            'admit_id' => 'required',
            'discharge_type' => 'required',
            'final_diagnosis' => 'required',
        ]);
        $patient_details = Patient::where('id',$request->patient_id)->get(['type','attended_doctor_id','bed_id','admit_date','discharge_date','entry_type']);
        $summary = new DischargeSummary();
        $summary->patient_id = $validated['patient_id'];
        $summary->admit_id = $validated['admit_id'];
        $summary->type = $patient_details[0]->type;
        $summary->doctor_id = $patient_details[0]->attended_doctor_id;
        $summary->bed_id = $patient_details[0]->bed_id;
        $summary->admit_date = $patient_details[0]->admit_date;
        $summary->discharge_date = $patient_details[0]->discharge_date ?? 'NA';
        $summary->patient_type = $patient_details[0]->entry_type;
        $summary->final_diagnosis = $validated['final_diagnosis'];
        if($summary->save()){
            Patient::where('id',$request->patient_id)->update([
                'discharge_form_generated' => 1,
                'discharge_type' => $validated['discharge_type']
            ]);
            AdmitList::where('patient_id',$request->patient_id)->where('admit_id',$request->admit_id)->update([
                'discharge_form_generated' => 1,
                'discharge_type' => $validated['discharge_type']
            ]);
            return response()->json(['success'=>'Discharge summary submited successfully'],200);
        }else{
            return response()->json(['error_success'=>'Discharge summary not submited']);
        }
    }
    public function summaryRepoet($id){
        $patient_logs = PatientLog::where('id',$id)->get(['patient_id','doctor_id']);
        $patients = Patient::where('id',$patient_logs[0]->patient_id)->get();
        $visitsData = Visit::with('doctorData')->where('patient_id',$id)->where('type','OPD')->get();
        $medicationData = Medication::with('medicineNameData')->where('patient_id',$id)->get();
        $vitalsData = Vital::where('patient_id',$patients[0]->id)->get();
        $labInvestigationData = LabInvestigation::with('testNameData')->where('patient_id',$id)->get();
        return view('backend.admin.modules.invoice.summary-report',compact('patient_logs','patients','visitsData','medicationData','vitalsData','labInvestigationData'));
    }
    public function advancePaymentPage($id){
        $patients = Patient::where('id',$id)->get();
        $advanve_amount = PaymentReceived::where('patient_id',$id)->where('amount_for','Advance')->get();
        return view('backend.admin.modules.invoice.advance-payment',compact('patients','advanve_amount'));
    }
     public function billPrint($id,$admit_id){
        $patient_id = $id;
        $previous_payment_bill = PaymentBill::where('patient_id', $id)->where('admit_id',$admit_id)->where('amount_for', 'Bed Charge')->latest('id')->first();
        $occupied_days = 0;
        $pre_bed_amount = 0;
        // if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
        //     $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
        //     $created_at = new DateTime($previous_payment_bill->created_at);
        //     $updated_at = new DateTime(); // Current date and time
        //     $interval = $created_at->diff($updated_at);
        //     $occupied_days = max((int)$interval->days, 1); // Ensure at least 1 day
        //     $pre_bed_amount = $bed_amount * $occupied_days;
        // }
          if($previous_payment_bill->amount == 0 || $previous_payment_bill->amount == NULL){
            $bed_amount = Bed::where('id', $previous_payment_bill->to_bed_id)->pluck('amount')->first(); // Get the actual amount value
           
            $admitTime = new DateTime($previous_payment_bill->created_at);
            $dischargeTime = new DateTime();

            // 2:00 PM cut-off
            $cutOffHour = 14;
            $days = 0;

            // Clone dates to avoid modifying originals
            $admitDate = clone $admitTime;
            $dischargeDate = clone $dischargeTime;

            // Only dates (set time to 00:00:00)
            $admitDate->setTime(0, 0, 0);
            $dischargeDate->setTime(0, 0, 0);

            // Difference in days (full calendar days)
            $intervalDays = $admitDate->diff($dischargeDate)->days;

            // Add intermediate full days (excluding first and last day)
            if ($intervalDays > 1) {
                $days += $intervalDays - 1;
            }

            // Admit day logic
            if ((int)$admitTime->format('H') < $cutOffHour) {
                $days += 1;
            }

            // Discharge day logic
            if ((int)$dischargeTime->format('H') < $cutOffHour) {
                $days += 1;
            }

            // Handle same-day admit/discharge
            if ($intervalDays === 0) {
                // If admitted and discharged same day, and either side meets the <2PM rule
                if ((int)$admitTime->format('H') < $cutOffHour || (int)$dischargeTime->format('H') < $cutOffHour) {
                    $days = 1;
                } else {
                    $days = 0;
                }
            }

            $occupied_days = $days;
            $pre_bed_amount = $bed_amount * $days;
        }
        $payment_bills = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->get();
        $total_amount = PaymentBill::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $received_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('amount');
        $discount_amount = PaymentReceived::where('patient_id',$id)->where('admit_id',$admit_id)->sum('discount_amount');
        return view('backend.admin.modules.invoice.discharge-bill-print',compact('payment_bills','patient_id','total_amount','received_amount','discount_amount','pre_bed_amount'));
    }
}
