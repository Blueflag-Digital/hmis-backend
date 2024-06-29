<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['person_id'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function visits()
    {
        return $this->hasMany(PatientVisit::class);
    }



    //reusable functions
    function getInitials()
    {
        $firstInitial =  isset($this->person) ? $this->person->first_name[0] : "";
        $lastInitial =  isset($this->person) ? $this->person->last_name[0] : "";

        // $firstInitial = isset($firstName) ? strtoupper($firstName[0]) : '';
        // $lastInitial = isset($lastName) ? strtoupper($lastName[0]) : '';
        return $firstInitial . $lastInitial;
    }


    //end reusbale code


    public function patientData()
    {
        return [
            'id' => $this->id,
            'initials' => isset($this->person)  ?  $this->getInitials()  : "",
            'name' =>  isset($this->person) ? $this->person->first_name . " " . $this->person->last_name : "",
            'dob' => isset($this->person) ? Carbon::parse($this->person->date_of_birth)->format('d/m/Y') : "",
            'gender' => isset($this->person) ? $this->person->gender : "",
            'phone' => isset($this->person) ? $this->person->phone : " ",
            'email' => isset($this->person) ? $this->person->email : "",
            'blood_group' => isset($this->person) ? $this->person->blood_group : "",
            'national_id' => isset($this->person) ? $this->person->identifier_number : "",
            // 'national_id' => isset($this->person) ? "23656524" : "",
            'city' => isset($this->person) && isset($this->person->city) ? $this->person->city->name : null,
        ];
    }
}
