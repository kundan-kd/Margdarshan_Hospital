<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use App\Models\BedGroup;
use App\Models\BedType;
use Illuminate\Http\Request;

class BedController extends Controller
{
     public function index(){
        $bedtypes = BedType::where('status',1)->get();
        $bedgroups = BedGroup::where('status',1)->get();
        return view('backend.admin.modules.master.bed',compact('bedtypes','bedgroups'));
    }
}
