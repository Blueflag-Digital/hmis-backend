<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','hospital_id',
    ];

    public function visits()
    {
        return $this->hasMany(PatientVisit::class);
    }
}
