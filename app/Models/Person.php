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
        'date_of_birth', 'gender', 'identifier_number', 'work_place_id'
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

    public function workPlace()
    {
        return $this->belongsTo(WorkPlace::class, 'work_place_id');
    }

    public function personPhone()
    {

        return isset($this->phones) ? $this->phones->map(function ($phone) {
            return $phone->phoneNumber();
        }) : [];
    }


    public function getAge()
    {
        return Carbon::parse($this->date_of_birth)->age . " years";
    }

     public function hospital()
    {
        return $this->belongsTo(Hospital::class,'hospital_id');
    }

    public function getName(){
        return $this->first_name . " " . $this->last_name;
    }



    public function personData()
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . " " . $this->last_name,
            'age' => $this->getAge(),
            'dob' => Carbon::parse($this->date_of_birth)->format('d/m/Y'),
            'gender' => $this->gender,
            'phones' => $this->personPhone(),
            'email' => $this->email,
            'blood_group' => $this->blood_group,
            'city' => $this->city ? $this->city->name : null,
            'city_id' => $this->city ? $this->city->id : null,
            'first_name' =>  $this->first_name,
            'last_name' => $this->last_name,
            'national_id' =>  $this->identifier_number,
            'work_place_id' => $this->work_place_id,
            'place_of_work' => isset($this->work_place_id) ?  $this->workPlace->name : "",
            'insurance_card_number' => $this->insurance_card_number,
            'med_insurance_card_number' => $this->med_Insurance_card_number
        ];
    }
}

