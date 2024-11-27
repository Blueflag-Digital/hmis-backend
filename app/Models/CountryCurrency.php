<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryCurrency extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
     public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
