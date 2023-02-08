<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriversTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the GET /drivers endpoint success status code.
     *
     * @return void
     */
    public function test_get_drivers_status_code_success(): void
    {
        $response = $this->get('api/drivers');

        $response->assertStatus(200);
    }

    /**
     * Test the GET /drivers endpoint failure status code.
     *
     * @return void
     */
    public function test_get_drivers_status_code_failure(): void
    {
        $response = $this->get('api/driver');

        $response->assertStatus(404);
    }

    /**
     * Test the GET /drivers endpoint.
     * Drivers found.
     *
     * @return void
     */
    public function test_get_drivers_json_structure_with_drivers_found(): void
    {
        // Arrange
        User::factory(5)->create();
        License::factory(5)->create();
        $drivers = Driver::factory(5)->create();
        foreach ($drivers as $driver) {
            Vehicle::factory()->create(['driver_id' => $driver->id]);
        }

        // Act
        $response = $this->get('api/drivers');
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(5, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Drivers found!',
            ]
        ]);

        $response->assertJsonFragment([
            'id' => $drivers[0]->id,
            'id_number' => $drivers[0]->id_number,
            'phone_number' => $drivers[0]->user->phone_number,
            'details' => [
                'id' => $drivers[0]->user->id,
                'home_address' => $drivers[0]->home_address,
                'first_name' => $drivers[0]->user->first_name,
                'last_name' => $drivers[0]->user->last_name,
                'license_type' => $drivers[0]->license->license_type,
                'last_trip_date' => $drivers[0]->date_of_last_trip,
            ],
            'vehicle' => [
                [
                    'id' => $drivers[0]->vehicles[0]->id,
                    'license_plate_number' => $drivers[0]->vehicles[0]->license_plate_number,
                    'vehicle_make' => $drivers[0]->vehicles[0]->vehicle_make,
                    'vehicle_model' => $drivers[0]->vehicles[0]->vehicle_model,
                    'year' => $drivers[0]->vehicles[0]->model_year,
                    'insured' => $drivers[0]->vehicles[0]->insured,
                    'service_date' => $drivers[0]->vehicles[0]->date_of_last_service,
                    'capacity' => $drivers[0]->vehicles[0]->passenger_capacity,
                ],
            ],
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'id_number',
                        'phone_number',
                        'details' => [
                            'id',
                            'home_address',
                            'first_name',
                            'last_name',
                            'license_type',
                            'last_trip_date',
                        ],
                        'vehicle' => [
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
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test the GET /drivers endpoint.
     * Drivers not found.
     *
     * @return void
     */
    public function test_get_drivers_json_structure_with_drivers_not_found(): void
    {
        // Act
        $response = $this->get('api/drivers');
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(0, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Drivers not found!',
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
            ],
        ]);
    }
}
