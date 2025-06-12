<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpdoutLabtest extends Model
{
    use HasFactory,SoftDeletes;
    public function testTypeData(){
        return $this->belongsTo(TestType::class,'test_type_id');
    }
    public function testNameData(){
        return $this->belongsTo(TestName::class,'test_name_id');
    }
}
