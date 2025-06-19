<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(){
        return view('backend.admin.modules.master.user');
    }
}
