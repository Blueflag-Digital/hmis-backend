<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'person_type_id', 'city_id', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'identifier_number'
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
      public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
