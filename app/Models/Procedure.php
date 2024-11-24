<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'hospital_id'];

    public function patientProcedures()
    {
        return $this->hasMany(PatientProcedure::class);
    }

    public function procedureData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }
}
