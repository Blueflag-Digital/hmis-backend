<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function hospitalData() {
        return [
            'name' => $this->name,
            'slug'=>$this->slug,
            'location' => $this->location,
            'contact'=> $this->contant,
        ];
    }
}
