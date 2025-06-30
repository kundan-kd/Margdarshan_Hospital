<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
   public function generateInvoice($id){
        // $payment_bills = PaymentBill::where('patient_id',)
        return view('backend.admin.modules.invoice.discharge-bill');
    }
}
