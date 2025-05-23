<?php
// BillingItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    protected $fillable = ['patient_visit_id', 'hospital_id', 'billable_type', 'billable_id', 'status', 'quantity', 'unit_price', 'amount'];

    public function billable()
    {
        return $this->morphTo();
    }

    public function patientVisit()
    {
        return $this->belongsTo(PatientVisit::class, 'patient_visit_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function calculateAmount()
    {
        if ($this->billable_type === PatientPrescription::class) {
            return $this->quantity * $this->billable->batch->selling_price;
        }
        return $this->quantity * $this->billable->price;
    }
}
