<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackSize extends Model
{
    use HasFactory;

    protected $fillable = ['name','hospital_id'];

    public function packSizeData(){
        return [
            'id'=>$this->id,
            'name'=> $this->name
        ];
    }


}
