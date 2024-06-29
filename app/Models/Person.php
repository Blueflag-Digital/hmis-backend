<?php

namespace App\Models;

use Carbon\Carbon;
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
        return $this->belongsTo(City::class, 'city_id');
    }

    public function personPhone()
    {

        return isset($this->phones) ? $this->phones->map(function ($phone) {
            return $phone->phoneNumber();
        }) : [];
    }


    public function personData()
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . " " . $this->last_name,
            'dob' => Carbon::parse($this->date_of_birth)->format('d/m/Y'),
            'gender' => $this->gender,
            'phones' => $this->personPhone(),
            'email' => $this->email,
            'blood_group' => $this->blood_group,
            'city' => $this->city ? $this->city->name : null,
            'city_id' => $this->city ? $this->city->id : null,
            'first_name' =>  $this->first_name,
            'last_name' => $this->last_name,
            'national_id' =>  $this->identifier_number
        ];
    }
}
