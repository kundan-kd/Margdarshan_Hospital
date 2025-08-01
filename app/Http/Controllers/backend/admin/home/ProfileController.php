<?php

namespace App\Http\Controllers\backend\admin\home;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\UserType;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {  
        $user = User::where('id', auth()->user()->id)->first();
        $departments = Department::get();
        $usertypes = UserType::get();
        return view('backend.admin.modules.profile',compact('user','departments','usertypes'));
    }

}
