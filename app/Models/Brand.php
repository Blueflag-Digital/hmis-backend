<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'drug_id'];


    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function brandData2()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'batches' => isset($this->batches) ? $this->batches->map(function ($batch) {
                return $batch->batchData();
            }) : [],
        ];
    }

    public function brandData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'drug' => isset($this->drug) ? $this->drug->drugData2() : null,
            'batches' => isset($this->batches) ? $this->batches->map(function ($batch) {
                return $batch->batchData();
            }) : [],
        ];
    }
}
