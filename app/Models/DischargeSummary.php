<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DischargeSummary extends Model
{
    public function bedData(){
        return $this->belongsTo(Bed::class,'bed_id');
    }
    public function doctorData(){
        return $this->belongsTo(User::class,'doctor_id');
    }
}
