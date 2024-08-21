<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProcedure extends Model
{
    use HasFactory;

    protected $fillable = ['procedure_id', 'quantity', 'consultation_id','description','hospital_id'];

    // Relationships
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
