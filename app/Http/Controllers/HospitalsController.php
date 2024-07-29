<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class HospitalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageNo = $request->pageNo;
        $limit = $request->limit;

        $data = [
            'data' => [],
            'status' => false,
        ];

        try {
            $data['totalCount'] = Hospital::count();
            $paginatedData = Hospital::latest()->paginate($limit, ['*'], 'page', $pageNo);
            $patients = $paginatedData->getCollection()->map(function ($hospital) {
                return $hospital->hospitalData();

            });
            $paginatedData->setCollection($patients);
            $data['data'] = $paginatedData;
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['status'] = false;
        try {

            $user = User::create([
                    'name' => $request->adminName,
                    'email' => $request->adminEmail,
                    'password' => Hash::make('12345678'),
                ]);

            if(!$user){
                throw new \Exception("Error Storing User", 1);
            }
            $role = Role::where('name',$request->role)->first();
            if(!$role){
                throw new \Exception("Role does not exist", 1);
            }
            $user->assignRole($role);
            if(!Hospital::create([
                'hospital_name'=> $request->name,
                'location'=> $request->location,
                'slug'=> Str::slug($request->name),
                'contact'=>$request->contact,
                'user_id' => $user->id
            ])){
                throw new \Exception("Hopsital not created", 1);
            }

            $data['status'] = true;
            $data['message'] = 'Hospital Created Succeffully';


        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hosp = Hospital::find($id);
        $hosp->delete();
        return response()->json(['data'=> true]);
    }
}
