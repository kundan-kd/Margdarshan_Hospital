<?php

namespace App\Http\Controllers\backend\admin\report;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PharmacyreportController extends Controller
{
    public function index(){
        return view('backend.admin.modules.report.pharmacy.expiry');
    }

    public function expiryData(Request $request)
    {
        if ($request->ajax()) {
            $today = now();
            $twoMonthsLater = now()->addMonths(2);

            $purchaseItems = PurchaseItem::get()->filter(function ($item) use ($twoMonthsLater) {
                // Convert "MM/YYYY" into a Carbon date (first day of month)
                $expiryDate = \Carbon\Carbon::createFromFormat('m/Y', $item->expiry)->startOfMonth();

                // Keep only items expiring within 2 months
                return $expiryDate->lessThanOrEqualTo($twoMonthsLater);
            });

            return DataTables::of($purchaseItems)
                ->addColumn('group', function ($row) {
                    return $row->categoryData->name ?? '';
                })
                ->addColumn('name', function ($row) {
                    return $row->medicineNameData->name ?? '';
                })
                ->addColumn('batch', function ($row) {
                    return $row->batch_no ?? '';
                })
                ->addColumn('qty', function ($row) {
                    $avl_qty = ($row->qty + $row->return_qty) - $row->stock_out;
                    return $avl_qty ?? '';
                })
                ->addColumn('expiry', function ($row) {
                    return $row->expiry ?? '';
                })
                ->make(true);
        }
    }

    public function profitMargin(){
        return view('backend.admin.modules.report.pharmacy.profit-margin');
    }

 public function profitMarginData(Request $request)
{
    if ($request->ajax()) {
        // Get all billings with required fields
        $billings = Billing::select('id', 'bill_no', 'created_at', 'total_amount')->get();

        // Get all billing items related to these billings
        $billingItems = BillingItem::whereIn('billing_id', $billings->pluck('id'))->get();

        return DataTables::of($billings)
            ->addColumn('bill_no', function ($row) {
                return $row->bill_no ?? '';
            })
            ->addColumn('bill_date', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('purchase_cost', function ($row) use ($billingItems) {
                // Calculate purchase cost as sum of purchase_price * qty
                $items = $billingItems->where('billing_id', $row->id);
                return $items->sum(function ($item) {
                    return $item->purchase_price * $item->qty;
                }) ?? 'NA';
            })
            ->addColumn('sales_amount', function ($row) {
                return $row->total_amount ?? 0;
            })
            ->addColumn('profit', function ($row) use ($billingItems) {
                $items = $billingItems->where('billing_id', $row->id);
                $purchaseCost = $items->sum(function ($item) {
                    return $item->purchase_price * $item->qty;
                });
                // return round(($row->total_amount ?? 0) - $purchaseCost);
                 $profit = ($row->total_amount ?? 0) - $purchaseCost;
                // Format with 2 decimal places
                return number_format($profit, 2, '.', '');

            })
            ->make(true);
    }
}

    
}
