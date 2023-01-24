<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        return response()->success(VehicleResource::collection($vehicles));
    }

    /**
     * Display the specified resource.
     * Display a listing of all the vehicles that belong specified driver.
     *
     * @param int $id
     * @return AnonymousResourceCollection
     */

    public function indexByDriverId(int $id)
    {
        $driver = Driver::findOrFail($id);
        $vehicles = $driver->vehicles()->get();
        return response()->success(VehicleResource::collection($vehicles));
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
     * @return Response
     */
    public function show(int $id)
    {

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
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        // validate the update request
        $validator = Validator::make($request->all(), [
            'license_plate_number' => 'required|string|max:255',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'model_year' => 'required|int',
            'insured' => 'required|boolean',
            'date_of_last_service' => 'required|date',
            'passenger_capacity' => 'required|int',
        ], [
            'license_plate_number.required' => 'License plate number is required',
            'vehicle_make.required' => 'Vehicle make is required',
            'vehicle_model.required' => 'Vehicle model is required',
            'model_year.required' => 'Year is required',
            'insured.required' => 'Insured is required',
            'date_of_last_service.required' => 'Date of last service is required',
            'passenger_capacity.required' => 'Passenger capacity is required',
        ]);

        $vehicle = Vehicle::findOrFail($id);

        if ($validator->fails()) {
            return response()->update('ERROR', false, 'Vehicle details could not be updated.', new VehicleResource($vehicle));
        } else {
            $validatedData = $validator->validated();

            // Update the vehicle record
            $vehicle->update($validatedData);

            // check if update was successful and return appropriate response
            $vehicle->save();
            if ($vehicle->wasChanged()) {
                return response()->update('OK', true, 'Vehicle details updated.', new VehicleResource($vehicle));
            } else {
                return response()->update('ERROR', false, 'Vehicle details could not be updated.', new VehicleResource($vehicle));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        //
    }
}
