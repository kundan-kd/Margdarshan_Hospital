<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
     public function userData(){
        return $this->belongsTo(User::class,'created_by');
    }
}
