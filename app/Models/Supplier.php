<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];

    public function batches()
    {
        return $this->hasMany(Batch::class,);
    }

    public function supplierData2(){
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public function  supplierData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_added' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'batches' => $this->batches->map(function ($batch) {
                return $batch->batchData();
            })
        ];
    }
}
