<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
   use HasFactory,SoftDeletes;
   public function categoryData(){
       return $this->belongsTo(MedicineCategory::class,'category_id');
   }
   public function medicineNameData(){
       return $this->belongsTo(Medicine::class,'name_id');
   }
}
