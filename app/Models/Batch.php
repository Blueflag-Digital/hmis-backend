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
        return $this->belongsTo(Drug::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
