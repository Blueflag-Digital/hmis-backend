<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPlace extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function people()
    {
        return $this->hasMany(Person::class);
    }

    public function workPlaceData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }



    public function workPlaceData2()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
