<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisCode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function diagnosisData(){
        return [
            'id' => $this->id,
            'code'=>$this->code,
            'name'=>$this->diagnosis
        ];
    }
}
