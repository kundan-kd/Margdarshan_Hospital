<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\Medicine;
use App\Models\Patient;
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
public function getPatientData(Request $request){
    $latestPatient = Patient::where('mobile', $request->mobile)
                            ->orderByDesc('created_at')
                            ->limit(1)
                            ->first(); // Gets the latest one directly from DB
    return response()->json(['success' => 'Latest patient data found','data' => $latestPatient], 200);
}
public function fillPatientData(Request $request){
    $getData = Patient::where('id',$request->id)->get();
    return response()->json(['success'=>'Patient detail found','data'=>$getData],200);

}
    
}
