<?php

namespace App\Http\Controllers\backend\admin\home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {  
        $user = User::where('id', auth()->user()->id)->first();
        // dd($user);
        return view('backend.admin.modules.profile',compact('user'));
    }

}
