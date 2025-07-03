<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class CommonController extends Controller
{
    public function getMedicineName(Request $request){
        $getData = Medicine::where('category_id',$request->id)->get();
        $getMedicineNameId = Medication::where('visit_id',$request->visit_id)->get(['medicine_name_id']);
        return response()->json(['success'=>'Medicine data fetched','data'=>$getData,'medicineNameId'=>$getMedicineNameId],200);
    }
public function barCodeGenerate()
{
    $data = '1234567890'; // Your barcode data
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($data, $generator::TYPE_CODE_128);

    return response($barcode)
        ->header('Content-Type', 'image/png');
}

    
}
