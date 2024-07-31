<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $fillable = ['name','hospital_id'];

    public function unitData(){
        return [
            'id'=>$this->id,
            'name'=> $this->name
        ];
    }
}
