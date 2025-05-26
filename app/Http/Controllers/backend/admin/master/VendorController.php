<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index()
    {
        return view('backend.admin.modules.master.vendor');
    }
    public function viewVendor(Request $request)
    {
         if($request->ajax()){
            $vendorData = Vendor::get();
            return DataTables::of($vendorData)
            ->addColumn('name',function($row){
                return $row->name;
            })
            ->addColumn('Phone',function($row){
                return $row->phone;
            })
            ->addColumn('email',function($row){
                return $row->email;
            })
            ->addColumn('address',function($row){
                return $row->address;
            })
            ->addColumn('gst_number',function($row){
                return $row->gst_number;
            })
            ->addColumn('status',function($row){
                $ischecked = $row->status == 1 ? 'checked':'';
                return '<div class="form-switch switch-primary">
                                <input class="form-check-input" type="checkbox" role="switch" onclick="statusSwitch('.$row->id.')"'.$ischecked.'>
                            </div>';
            })
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit" onclick="vendorEdit('.$row->id.')"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line" onclick="vendorDelete('.$row->id.')"></iconify-icon>
                </a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
    }
     public function addVendor(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'gst' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error_validation'=> $validator->errors()->all(),],422);
        }
        $vendors = new Vendor();
        $vendors->name = $request->name;
        $vendors->phone = $request->phone;
        $vendors->email = $request->email;
        $vendors->address = $request->address;
        $vendors->gst_number = $request->gst;
        if($vendors->save()){
            return response()->json(['success'=>'Vendor added successfully'],201);
        }else{
            return response()->json(['error_success'=>'Vendor not added'],500);

        }
    }
    public function getVendorData(Request $request){
        $getData = Vendor::where('id',$request->id)->get();
        return response()->json(['success'=>'Vendor data fetched successfully','data'=>$getData],200);
    }
    public function updateVendorData(Request $request){
        Vendor::where('id',$request->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'gst_number' => $request->gst
        ]);
       return response()->json(['success' => 'Vendor updated successfully'],200);
    }
    public function statusUpdate(Request $request){
        $vendorstatus = Vendor::where('id',$request->id)->get(['status']);
        $new_status = 1;
        if($vendorstatus[0]->status == 1){
            $new_status = 0;
        }
        Vendor::where('id',$request->id)->update([
            'status' => $new_status
        ]);
        return response()->json(['success' => 'Vendor Status Updated Successfully'],200);
    }
    public function deleteVendor(Request $request){
        Vendor::where('id',$request->id)->delete();
        return response()->json(['success' => 'Vendor Deleted Successfully'],200);
    }
}
