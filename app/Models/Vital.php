<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vital extends Model
{
    use SoftDeletes,HasFactory;
    public function patientData()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
