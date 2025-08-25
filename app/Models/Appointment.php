<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;
    public function patient_data(){
        return $this-> hasOne('App\Models\Patient','id','patient_id');
    }
    public function user_data(){
        return $this-> hasOne('App\Models\User','id','doctor_id');
    }
    public function roomNumberData(){
        return $this-> belongsTo(RoomNumber::class,'room_number');
    }
}
