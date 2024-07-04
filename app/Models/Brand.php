<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name','drug_id'];


    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }


    public function brandData()
    {
        return [
            'name' => $this->name,
            'drug' => isset($this->drug) ? $this->drug->drugData2() :null,
        ];
    }
}
