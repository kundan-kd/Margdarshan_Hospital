<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory,SoftDeletes;
    public function userData(){
        return $this->belongsTo(User::class,'assign_to');
    }
    public function narationListData(){
        return $this->belongsTo(NarationList::class,'naration_list_id');
    }
}
