<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $validator = Validator::make($request->only(['make', 'service_date', 'age']), [
                'make' => 'string|max:30',
                'service_date' => 'date',
                'age' => 'integer|max:100'
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->update('ERROR', false, $validator->errors()->first(), 400);
            }

            $validatedData = $validator->validated();

            $vehicles = Vehicle::query();

            if ($request->has('make')) {
                $vehicles = $vehicles->vehicleMake($validatedData['make']);
            }

            if ($request->has('service_date')) {
                $vehicles = $vehicles->serviceDate($validatedData['service_date']);
            }

            if ($request->has('age')) {
                $vehicles = $vehicles->modelAge($validatedData['age']);
            }

            $vehicles = $vehicles->paginate($perPage, ['*'], 'page', $page);

            if ($vehicles->isNotEmpty()) {
                return response()->update('OK', true, 'Vehicles found!', VehicleResource::collection($vehicles), 200);
            }
            return response()->update('ERROR', false, 'Vehicles not found!', VehicleResource::collection($vehicles), 404);

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Vehicles not found!', 404);
        }
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
        try {
            $driver = Driver::findOrFail($id);
            $vehicles = $driver->vehicles()->get();

            // check if the driver has any vehicles
            if ($vehicles->isNotEmpty()) {
                return response()->update('OK', true, 'Driver vehicle(s) found!', VehicleResource::collection($vehicles), 200);
            }
            return response()->update('ERROR', false, 'Driver vehicle(s) not found!', VehicleResource::collection($vehicles), 404);

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Driver vehicle(s) not found!', 404);
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
                'license_plate_number' => 'required|string|max:255',
                'vehicle_make' => 'required|string|max:255',
                'vehicle_model' => 'required|string|max:255',
                'model_year' => 'required|int',
                'insured' => 'required|boolean',
                'date_of_last_service' => 'required|date',
                'passenger_capacity' => 'required|int',
                'driver_id' => 'required|int',
            ], [
                'license_plate_number.required' => 'License plate number is required',
                'vehicle_make.required' => 'Vehicle make is required',
                'vehicle_model.required' => 'Vehicle model is required',
                'model_year.required' => 'Year is required',
                'insured.required' => 'Insured is required',
                'date_of_last_service.required' => 'Date of last service is required',
                'passenger_capacity.required' => 'Passenger capacity is required',
                'driver_id.required' => 'Driver ID is required',
            ]);

            // check the validation
            if ($validator->fails()) {
                return response()->update('ERROR', false, 'Vehicle could not be created!', 400);
            }

            $validatedData = $validator->validated();
            $driver = Driver::findOrFail($validatedData['driver_id']);

            //check if the driver has any vehicles
            if ($driver->vehicles()->get()->isNotEmpty()) {
                // check if the driver has a vehicle with the same license plate number
                $vehicles = $driver->vehicles()->where('license_plate_number', $validatedData['license_plate_number'])->get();
                if ($vehicles->isNotEmpty()) {
                    return response()->update('ERROR', false, 'Vehicle could not be created!', new VehicleResource($vehicles->first()), 400);
                }
            }

            // Create the vehicle record
            $vehicle = Vehicle::create($validatedData);
            return response()->update('OK', true, 'Vehicle created!', new VehicleResource($vehicle), 201);

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Vehicle could not be created!', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        //
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
        try {
            // validate the update request
            $validator = Validator::make($request->all(), [
                'id' => 'required|int',
                'license_plate_number' => 'required|string|max:255',
                'vehicle_make' => 'required|string|max:255',
                'vehicle_model' => 'required|string|max:255',
                'model_year' => 'required|int',
                'insured' => 'required|boolean',
                'date_of_last_service' => 'required|date',
                'passenger_capacity' => 'required|int',
            ], [
                'id.required' => 'Vehicle ID is required',
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
                return response()->update('ERROR', false, 'Vehicle details could not be updated.', new VehicleResource($vehicle), 400);
            }
            $validatedData = $validator->validated();

            // Update the vehicle record
            $vehicle->update($validatedData);
            return response()->update('OK', true, 'Vehicle details updated.', new VehicleResource($vehicle), 200);

        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Vehicle details could not be updated.', 400);
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
        try {
            // Delete the vehicle record
            $vehicle = Vehicle::findOrFail($id);

            if ($vehicle->delete()) {
                return response()->update('OK', true, 'Vehicle deleted!', 200);
            } else {
                return response()->update('ERROR', false, 'Vehicle could not be deleted.', 400);
            }
        } catch (\Exception $e) {
            return response()->update('ERROR', false, 'Vehicle could not be deleted.', 400);
        }
    }
}
