<?php

namespace Tests\Feature\VehiclesEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetVehiclesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the GET /vehicles endpoint success status code.
     *
     * @return void
     */
    public function test_get_vehicles_status_code_success(): void
    {
        // Arrange
        User::factory(5)->create();
        License::factory(5)->create();
        $drivers = Driver::factory(5)->create();
        foreach ($drivers as $driver) {
            Vehicle::factory()->create(['driver_id' => $driver->id]);
        }

        // Act
        $response = $this->get('api/vehicles');

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the GET /vehicles endpoint failure status code.
     *
     * @return void
     */
    public function test_get_vehicles_status_code_failure(): void
    {
        // Arrange
        User::factory(5)->create();
        License::factory(5)->create();
        Driver::factory(5)->create();

        // Act
        $response = $this->get('api/vehicles');

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the GET /vehicles endpoint.
     * Get all vehicles with vehicles found
     *
     * @return void
     */
    public function test_get_vehicles_with_vehicles_found(): void
    {
        // Arrange
        User::factory(5)->create();
        License::factory(5)->create();
        $drivers = Driver::factory(5)->create();
        foreach ($drivers as $driver) {
            Vehicle::factory()->create(['driver_id' => $driver->id]);
        }

        // Act
        $response = $this->get('api/vehicles');
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(5, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Vehicles found!',
            ]
        ]);

        $response->assertJsonFragment([
            'license_plate_number' => $drivers[0]->vehicles[0]->license_plate_number,
            'vehicle_make' => $drivers[0]->vehicles[0]->vehicle_make,
            'vehicle_model' => $drivers[0]->vehicles[0]->vehicle_model,
            'year' => $drivers[0]->vehicles[0]->model_year,
            'insured' => $drivers[0]->vehicles[0]->insured,
            'service_date' => $drivers[0]->vehicles[0]->date_of_last_service,
            'capacity' => $drivers[0]->vehicles[0]->passenger_capacity,
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
                    '*' => [
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
                ],
            ]
        ]);
    }

    /**
     * Test the GET /vehicles endpoint.
     * Get all vehicles with no vehicles found
     *
     * @return void
     */
    public function test_get_vehicles_with_no_vehicles_found(): void
    {
        // Arrange
        User::factory(5)->create();
        License::factory(5)->create();
        Driver::factory(5)->create();

        // Act
        $response = $this->get('api/vehicles');
        $data = $response->json();

        // Assert
        $response->assertStatus(404);
        $this->assertCount(0, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Vehicles not found!',
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
}
