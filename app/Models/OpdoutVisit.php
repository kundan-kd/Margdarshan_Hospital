<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpdoutVisit extends Model
{
    use HasFactory,SoftDeletes;
    public function patientData(){
        return $this->belongsTo(Patient::class,'patient_id');
    }
    public function doctorData(){
        return $this->belongsTo(User::class,'consultDoctor');
    }
    
}
