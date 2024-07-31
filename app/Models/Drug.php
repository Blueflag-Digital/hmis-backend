<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id', 'name','hospital_id'];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function drugData2()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function drugData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
