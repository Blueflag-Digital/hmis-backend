<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'quantity_received', 'quantity_available', 'supplier_id', 'lpo',
        'buying_price', 'selling_price', 'pack_size_id', 'unit_of_measure_id'
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id');
    }
    public function packSize()
    {
        return $this->belongsTo(PackSize::class, 'pack_size_id');
    }


    public function batchData()
    {
        return [
            'id' => $this->id,
            'supplier' => isset($this->supplier) ? $this->supplier->supplierData2() : null,
            'brand' => isset($this->brand) ? $this->brand->brandData3() : null,
            'unit_of_measure' => isset($this->unit) ? $this->unit->unitData() : null,
            'quantity_received' => $this->quantity_received,
            'quantity_available' => $this->quantity_available,
            'lpo' => $this->lpo,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'pack_size' =>  isset($this->packSize) ? $this->packSize->packSizeData() : null,
        ];
    }
}
