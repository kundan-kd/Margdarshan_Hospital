<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomNumber extends Model
{
    use HasFactory,SoftDeletes;
    public function roomtypeData(){
        return $this->belongsTo(RoomType::class,'roomtype_id');
    }
    public function roomGroupData(){
        return $this->belongsTo(BedGroup::class,'room_group_id');
    }
}
