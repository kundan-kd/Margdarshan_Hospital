<?php

namespace App\Http\Controllers\backend\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use HasFactory,SoftDeletes;
}
