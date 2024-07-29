<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subscription(){
        return $this->hasOne(Subscription::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }


    public function hospitalData() {
        return [
            'name' => $this->hospital_name,
            'slug'=>$this->slug,
            'location' => $this->location,
            'contact'=> $this->contact,
            'admin_name' => isset($this->user) ? $this->user->name : "",
            'date_paid' => isset($this->payments)  && ($this->payments->isNotEmpty() ) ? $this->payments()->latest()->first()->created_at->diffForHumans()  : "",
            'subscription_status' => isset($this->subscription) && $this->subscription->status === 'true' ? true  : false,
        ];
    }
}
