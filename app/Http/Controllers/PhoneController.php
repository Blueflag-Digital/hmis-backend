<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function store(Request $request, $person_id)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|string|max:20|unique:phones',
        ]);

        $person = Person::findOrFail($person_id);

        $phone = new Phone();
        $phone->person_id = $person->id;
        $phone->phone_number = $validatedData['phone_number'];
        $phone->save();

        return response()->json($phone, 201);
    }

    public function index($person_id)
    {
        $person = Person::findOrFail($person_id);
        $phones = $person->phones;

        return response()->json($phones);
    }

    /**
     * Display the specified resource.
     */
    public function show(Phone $phone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone)
    {
        //
    }
}
