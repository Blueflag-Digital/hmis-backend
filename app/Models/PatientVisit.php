<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'department_id',
        'status',
        'checked_in_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function billingItems(){
        return $this->hasMany(BillingItem::class);
    }

    public function visitData()
    {
        return [
            'id' => $this->id,
            'department_name' => $this->department->name ?? 'Unknown', // Default to 'Unknown' if null
            'status' => $this->status,
            'checkin_date' => Carbon::parse($this->created_at)->format('d/m/Y'),

        ];
    }
}
