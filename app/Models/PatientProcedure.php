<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProcedure extends Model
{
    use HasFactory;

    protected $fillable = ['procedure_id', 'quantity', 'consultation_id', 'description', 'hospital_id'];

    // Relationships
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function getPatientProcedureData()
    {
        return [
            'id' => $this->id,
            'procedure' => $this->procedure->name,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'dateDone' => isset($this->created_at) ? Carbon::parse($this->created_at)->format('d/m/Y') : "",
        ];
    }
}
