<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'billing_item_id', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function billingItem()
    {
        return $this->belongsTo(BillingItem::class);
    }
}
