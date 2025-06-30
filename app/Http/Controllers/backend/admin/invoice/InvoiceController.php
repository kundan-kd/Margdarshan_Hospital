<?php

namespace App\Http\Controllers\backend\admin\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
   public function generateInvoice(){
        return view('backend.admin.modules.invoice.discharge-bill');
    }
}
