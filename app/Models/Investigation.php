<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    use HasFactory;

    public function consultations()
    {
        return $this->belongsToMany(Consultation::class, 'patient_investigation')
            ->withPivot('status', 'results', 'ordered_at', 'updated_at')
            ->withTimestamps()
            ;

    }
}
