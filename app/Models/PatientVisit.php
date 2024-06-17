<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'department_id',
        'status',
        'checked_in_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
