<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_id', 'quantity_received', 'quantity_available', 'supplier_id', 'lpo',
        'buying_price', 'selling_price', 'pack_size_id', 'unit_of_measure_id'
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }


    public function batchData()
    {
        return [
            'id' => $this->id,
            'drug' => isset($this->drug) ? $this->drug->drugData() : null,
            'quantity_received' => $this->quantity_received,
            'quantity_available' => $this->quantity_available,
            'lpo' => $this->lpo,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'pack_size_id' => $this->pack_size_id
        ];
    }
}
