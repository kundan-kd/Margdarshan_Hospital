<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getMedicineName(Request $request){
    $getData = Medicine::where('category_id',$request->id)->get();
    return response()->json(['success'=>'Medicine data fetched','data'=>$getData],200);
    }
}
