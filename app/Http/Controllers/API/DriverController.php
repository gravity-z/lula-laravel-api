<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverDetailsResource;
use App\Http\Resources\DriverResource;
use App\Http\Resources\DriverUpdateResource;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $drivers = Driver::with('user', 'vehicles', 'license')->get();
        return response()->success(DriverResource::collection($drivers));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $driver = Driver::with('user', 'vehicles', 'license')->findOrFail($id);
        return response()->success([new DriverResource($driver)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        // validate the update request
        $validator = Validator::make($request->all(), [
            'home_address' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'licence_type' => 'required|in:A,B,C,D',
            'last_trip_date' => 'required|date',
        ], [
            'home_address.required' => 'The home address is required',
            'first_name.required' => 'The first name is required',
            'last_name.required' => 'The last name is required',
            'licence_type.required' => 'The licence type is required',
            'last_trip_date.required' => 'The last trip date is required',
        ]);

        $driver = Driver::findOrFail($id);

        if ($validator->fails()) {
            return response()->update('ERROR', false, 'Driver account could not be updated!', new DriverDetailsResource($driver));
        } else {
            $validatedData = $validator->validated();

            // Update the driver record
            if (array_key_exists('home_address', $validatedData) && array_key_exists('last_trip_date', $validatedData)) {
                $driver->update([
                    'home_address' => $validatedData['home_address'],
                    'last_trip_date' => $validatedData['last_trip_date'],
                ]);
            }

            // Update the user record
            $user = $driver->user;
            if (array_key_exists('first_name', $validatedData) && array_key_exists('last_name', $validatedData)) {
                $user->update([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                ]);
            }

            // Update the license record
            $license = $driver->license;
            if (array_key_exists('licence_type', $validatedData)) {
                $license->update([
                    'licence_type' => $validatedData['licence_type'],
                ]);
            }

            // check if update was successful and return appropriate response
            if ($driver->save() && $user->save() && $license->save()) {
                return response()->update('OK', true, 'Driver information updated!', new DriverDetailsResource($driver));
            } else {
                return response()->update('ERROR', false, 'Driver account could not be updated!', new DriverDetailsResource($driver));
            }
        }
    }

    /**
     * Patch the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function patch(Request $request, int $id) {

        // Validate the update request
        $validator = Validator::make($request->all(), [
            'id_number' => 'required_without:phone_number|digits:13',
            'phone_number' => 'required_without:id_number|digits:10',
        ], [
            'id_number.required_without' => 'The ID number is required',
            'phone_number.required_without' => 'The phone number is required',
        ]);

        $driver = Driver::findOrFail($id);

        if ($validator->fails()) {
            return response()->update('ERROR', false, 'Driver account could not be updated!', new DriverUpdateResource($driver));
        } else {
            $validatedData = $validator->validated();

            // Patch the driver record
            if (array_key_exists('id_number', $validatedData)) {
                $driver->update([
                    'id_number' => $validatedData['id_number'],
                ]);
            }

            //Patch the user record
            $user = $driver->user;
            if (array_key_exists('phone_number', $validatedData)) {
                $user->update([
                    'phone_number' => $validatedData['phone_number'],
                ]);
            }

            // formulate the response
            if ($driver->save() or $user->save()) {
                return response()->update('OK', true, 'Driver account updated!', new DriverUpdateResource($driver));
            } else {
                return response()->update('ERROR', false, 'Driver account could not be updated!', new DriverUpdateResource($driver));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $user = $driver->user;
        $license = $driver->license;

        if ($driver->delete() && $user->delete() && $license->delete()) {
            return response()->update('OK', true, 'Driver account deleted!');
        } else {
            return response()->delete('ERROR', false, 'Driver account could not be deleted!');
        }
    }
}
