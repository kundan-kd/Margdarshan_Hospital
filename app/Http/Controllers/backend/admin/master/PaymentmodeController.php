<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentmodeController extends Controller
{
    public function index(){
        return view('backend.admin.modules.master.payment-mode');
    }
    public function viewPaymentModes(Request $request){
        if($request->ajax()){
            $usertype = PaymentMode::get();
            return DataTables::of($usertype)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="paymentModeEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="paymentModeDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
    public function addPaymentMode(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(),[
            'paymentmode' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=> $validator->errors()->all(),],422);
        }
        $paymentmode = new PaymentMode();
        $paymentmode->name = $request->paymentmode;
        if($paymentmode->save()){
            return response()->json(['success'=>'Payment Mode added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Payment Mode not added'],500);

        }
    }

    public function getPaymentModeData(Request $request){
        $getData = PaymentMode::where('id',$request->id)->get();
        return response()->json(['success'=>'Payment Mode fetched successfully','data'=>$getData],200);
    }
    public function updatePaymentModeData(Request $request){
        PaymentMode::where('id',$request->id)->update([
            'name' => $request->paymentmode
        ]);
       return response()->json(['success' => 'Payment Mode updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $paymentmode_status = PaymentMode::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($paymentmode_status[0]->status == 1){
            $new_status = 0;
        }
        PaymentMode::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Payment Mode Status Updated Successfully'],200);
    }
    public function deletePaymentModeData(Request $request){
        PaymentMode::where('id',$request->id)->delete();
        return response()->json(['success' => 'Payment Mode Deleted Successfully'],200);
    }
}
