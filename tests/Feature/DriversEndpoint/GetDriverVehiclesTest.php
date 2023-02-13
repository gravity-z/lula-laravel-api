<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriverVehiclesTest extends TestCase
{
    use RefreshDatabase;
    use withFaker;

    /**
     * Test the GET /drivers/{id}/vehicle endpoint success status code.
     *
     * @return void
     */
    public function test_get_driver_vehicles_status_code_success(): void
    {
        $response = $this->get('api/drivers/1/vehicle');

        $response->assertStatus(200);
    }

    /**
     * Test the GET /drivers/{id}/vehicle endpoint failure status code.
     *
     * @return void
     */
    public function test_get_driver_vehicles_status_code_failure(): void
    {
        $response = $this->get('api/drivers/1/vehicles');

        $response->assertStatus(404);
    }

    /**
     * Test the GET /drivers/{id}/vehicle endpoint.
     * Driver found, vehicles found.
     *
     * @return void
     */
    public function test_get_driver_vehicle_with_driver_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory(1)->create(['driver_id' => $driver->id]);

        // Act
        $response = $this->get("api/drivers/{$driver->id}/vehicle");
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(1, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver vehicle(s) found!',
            ]
        ]);

        $response->assertJsonFragment([
            [
                'id' => $vehicle[0]->id,
                'license_plate_number' => $vehicle[0]->license_plate_number,
                'vehicle_make' => $vehicle[0]->vehicle_make,
                'vehicle_model' => $vehicle[0]->vehicle_model,
                'year' => $vehicle[0]->model_year,
                'insured' => $vehicle[0]->insured,
                'service_date' => $vehicle[0]->date_of_last_service,
                'capacity' => $vehicle[0]->passenger_capacity,
            ]
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
                    [
                        'id',
                        'license_plate_number',
                        'vehicle_make',
                        'vehicle_model',
                        'year',
                        'insured',
                        'service_date',
                        'capacity',
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test the GET /drivers/{id}/vehicle endpoint.
     * Driver found, vehicles not found.
     *
     * @return void
     */
    public function test_get_driver_vehicle_with_driver_found_and_vehicles_not_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();

        // Act
        $response = $this->get("api/drivers/{$driver->id}/vehicle");
        $data = $response->json();

        // Assert
        $response->assertStatus(404);
        $this->assertCount(0, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Driver vehicle(s) not found!',
            ]
        ]);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data',
            ]
        ]);
    }

/**
     * Test the GET /drivers/{id}/vehicle endpoint.
     * Driver found, multiple vehicles found.
     *
     * @return void
     */
    public function test_get_driver_vehicle_with_driver_found_and_multiple_vehicles_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory(2)->create(['driver_id' => $driver->id]);

        // Act
        $response = $this->get("api/drivers/{$driver->id}/vehicle");
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(2, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver vehicle(s) found!',
            ]
        ]);
        $response->assertJsonFragment([
            [
                [
                    'id' => $vehicle[0]->id,
                    'license_plate_number' => $vehicle[0]->license_plate_number,
                    'vehicle_make' => $vehicle[0]->vehicle_make,
                    'vehicle_model' => $vehicle[0]->vehicle_model,
                    'year' => $vehicle[0]->model_year,
                    'insured' => $vehicle[0]->insured,
                    'service_date' => $vehicle[0]->date_of_last_service,
                    'capacity' => $vehicle[0]->passenger_capacity,
                ],
                [
                    'id' => $vehicle[1]->id,
                    'license_plate_number' => $vehicle[1]->license_plate_number,
                    'vehicle_make' => $vehicle[1]->vehicle_make,
                    'vehicle_model' => $vehicle[1]->vehicle_model,
                    'year' => $vehicle[1]->model_year,
                    'insured' => $vehicle[1]->insured,
                    'service_date' => $vehicle[1]->date_of_last_service,
                    'capacity' => $vehicle[1]->passenger_capacity,
                ],
            ]
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
                    [
                        'id',
                        'license_plate_number',
                        'vehicle_make',
                        'vehicle_model',
                        'year',
                        'insured',
                        'service_date',
                        'capacity',
                    ],
                    [
                        'id',
                        'license_plate_number',
                        'vehicle_make',
                        'vehicle_model',
                        'year',
                        'insured',
                        'service_date',
                        'capacity',
                    ],
                ]
            ]
        ]);
    }
}
