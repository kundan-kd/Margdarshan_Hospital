<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory,SoftDeletes;
    public function categoryData(){
        return $this->belongsTo(MedicineCategory::class,'category_id');
    }
    public function companyData(){
        return $this->belongsTo(Company::class,'company_id');
    }
    public function groupData(){
        return $this->belongsTo(MedicineGroup::class,'group_id');
    }
    public function unitData(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
