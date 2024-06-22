<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Person;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($person_id)
    {
        $person = Person::findOrFail($person_id);
        $email_addresses = $person->emails;

        return response()->json($email_addresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $person_id)
    {
        $validatedData = $request->validate([
            'email_address' => 'required|string|max:20|unique:phones',
        ]);

        $person = Person::findOrFail($person_id);

        $email = new Email();
        $email->person_id = $person->id;
        $email->email_address = $validatedData['email_address'];
        $email->save();

        return response()->json($email, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email)
    {
        //
    }
}
