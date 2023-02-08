<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverDetailsResource;
use App\Http\Resources\DriverResource;
use App\Http\Resources\DriverUpdateResource;
use App\Models\Driver;
use App\Models\License;
use App\Models\User;
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
        try {
            $drivers = Driver::with('user', 'vehicles', 'license')->get();

            if ($drivers->isNotEmpty()) {
                return response()->update('OK', true, 'Drivers found!', DriverResource::collection($drivers));
            } else {
                return response()->update('ERROR', false, 'Drivers not found!', DriverResource::collection($drivers));
            }
        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Drivers not found!', []);
        }
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
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'id_number' => 'required:phone_number|digits:13',
                'phone_number' => 'required:id_number|digits:10',
                'home_address' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'licence_type' => 'required|in:A,B,C,D|size:1',
            ], [
                'id_number.required' => 'The ID number is required',
                'phone_number.required' => 'The phone number is required',
                'home_address.required' => 'The home address is required',
                'first_name.required' => 'The first name is required',
                'last_name.required' => 'The last name is required',
                'licence_type.required' => 'The licence type is required',
            ]);

            if ($validator->fails()) {
                return response()->update('ERROR', false, 'Driver account could not be created!');
            }

            $validatedData = $validator->validated();

            // Create the user record
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone_number' => $validatedData['phone_number'],
                'email' => 'sa@lulaloop.co.za',
                'password' => bcrypt('password'),
            ]);

            // Create the driver license record
            $license = License::create([
                'license_type' => $validatedData['licence_type'],
            ]);

            // Create the driver record
            $driver = Driver::create([
                'user_id' => $user->id,
                'license_id' => $license->id,
                'id_number' => $validatedData['id_number'],
                'home_address' => $validatedData['home_address'],
            ]);
            return response()->update('OK', true, 'Driver account created!', new DriverResource($driver));

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver account could not be created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            $driver = Driver::with('user', 'vehicles', 'license')->findOrFail($id);

            return response()->update('OK', true, 'Found driver account!', new DriverResource($driver));
        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Could not find driver account!', []);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
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
        try {
            // validate the update request
            $validator = Validator::make($request->all(), [
                'home_address' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'licence_type' => 'required|in:A,B,C,D|size:1',
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
            }
            $validatedData = $validator->validated();

            // Update the driver record
            if (array_key_exists('home_address', $validatedData) && array_key_exists('last_trip_date', $validatedData)) {
                $driver->update([
                    'home_address' => $validatedData['home_address'],
                    'date_of_last_trip' => $validatedData['last_trip_date'],
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

            return response()->update('OK', true, 'Driver information updated!', new DriverDetailsResource($driver));

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver account could not be updated!', []);
        }
    }

    /**
     * Patch the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function patch(Request $request, int $id)
    {

        try {
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
            }
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
            return response()->update('OK', true, 'Driver account updated!', new DriverUpdateResource($driver));

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver account could not be updated!', []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, int $id)
    {
        // Delete the driver details
        try {
            if ($request->is('api/drivers/*/details')) {
                $driver = Driver::findOrFail($id);
                $license = $driver->license;
                $license->delete();

                return response()->update('OK', true, 'Driver information deleted!');
            }
        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver information could not be deleted!');
        }

            // Delete the driver account
        try {
            $driver = Driver::findOrFail($id);
            $user = $driver->user;
            $license = $driver->license;

            $user->delete();
            $license->delete();
            return response()->update('OK', true, 'Driver account deleted!');
        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver account could not be deleted!');
        }
    }
}
