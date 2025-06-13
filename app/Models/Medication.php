<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory,SoftDeletes;
    public function medicineCategoryData(){
        return $this->belongsTo(MedicineCategory::class,'medicine_category_id');
    }
    public function medicineNameData(){
        return $this->belongsTo(Medicine::class,'medicine_name_id');
    }
}
