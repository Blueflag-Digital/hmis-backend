<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patient(){
        return $this->belongsTo(Patient::class,'patient_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
