<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    use HasFactory,SoftDeletes;
    public function bedGroupData(){
        return $this->belongsTo(BedGroup::class,'bed_group_id');
    }
    public function bedTypeData(){
        return $this->belongsTo(BedType::class,'bed_type_id');
    }
}
