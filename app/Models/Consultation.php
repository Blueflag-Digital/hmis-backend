<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'diagnosis_ids' => 'array',
    ];

    public function investigations()
    {
        return $this->belongsToMany(Investigation::class, 'patient_investigation')
            ->withPivot('status', 'results','file_path','created_at', 'updated_at')
            ->withTimestamps();
    }
}
