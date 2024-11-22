<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'drug_id',
        'dosage',
        'number_dispensed',
        'results',
        'batch_id',
        'hospital_id'
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function consultation(){
         return $this->belongsTo(Consultation::class,'consultation_id'); 
    }

    public function prescriptionData()
    {
        return [
            'id' => $this->id,
            'drug' => isset($this->drug) ? $this->drug->drugData2() : null,
            'brand' => isset($this->batch) && isset($this->batch->brand) ? $this->batch->brand->brandData3() : null,
            'dosage' => $this->dosage,
            'number_dispensed' =>  $this->number_dispensed,
            'date_added' => Carbon::parse($this->created_at)->format('d/m/Y')
        ];
    }
}
