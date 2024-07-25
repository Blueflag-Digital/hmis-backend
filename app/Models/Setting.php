<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function settingsData(){
        return [
            'id'=> $this->id,
            'name' => $this->name,
            'description'=> $this->description,
            'value'=> $this->value
        ];
    }
}
