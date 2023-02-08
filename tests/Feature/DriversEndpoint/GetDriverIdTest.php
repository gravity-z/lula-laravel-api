<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriverIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the GET /drivers/{id} endpoint success status code.
     *
     * @return void
     */
    public function test_get_driver_status_code_success(): void
    {
        $response = $this->get('api/drivers/1');

        $response->assertStatus(200);
    }

    /**
     * Test the GET /drivers/{id} endpoint failure status code.
     *
     * @return void
     */
    public function test_get_driver_status_code_failure(): void
    {
        $response = $this->get('api/driver/0');

        $response->assertStatus(404);
    }

    /**
     * Test the GET /drivers/{id} endpoint.
     * Driver found with no vehicles.
     *
     * @return void
     */
    public function test_get_driver_with_driver_found_without_vehicles(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();

        // Act
        $response = $this->get("api/drivers/{$driver->id}");
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertCount(5, $data[0]['data']);
        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Found driver account!',
            ]
        ]);

        $response->assertJsonFragment([
            [
                'id' => $driver->id,
                'id_number' => $driver->id_number,
                'phone_number' => $driver->user->phone_number,
                'details' => [
                    'id' => $driver->user->id,
                    'home_address' => $driver->home_address,
                    'first_name' => $driver->user->first_name,
                    'last_name' => $driver->user->last_name,
                    'license_type' => $driver->license->license_type,
                    'last_trip_date' => $driver->date_of_last_trip,
                ],
                'vehicle' => [],
            ]
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
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
                    'vehicle' => [],
                ],
            ]
        ]);
    }

    /**
     * Test the GET /drivers/{id} endpoint.
     * Driver found with one vehicle.
     *
     * @return void
     */
//    public function test_get_driver_with_driver_found_with_one_vehicle(): void
//    {
//        // Arrange
//        User::factory()->create();
//        License::factory()->create();
//        $driver = Driver::factory()->create();
//        Vehicle::factory()->create(['driver_id' => $driver->id]);
//
//        // Act
//        $response = $this->get('api/drivers/1');
//        $data = $response->json();
//
//        // Assert
//        $response->assertStatus(200);
//        $this->assertCount(5, $data[0]['data']);
//        $response->assertJson([
//            [
//                'status' => 'OK',
//                'success' => true,
//                'message' => 'Found driver account!',
//            ]
//        ]);
//
//        $response->assertJsonFragment([
//            [
//                'id' => $driver->id,
//                'id_number' => $driver->id_number,
//                'phone_number' => $driver->user->phone_number,
//                'details' => [
//                    'id' => $driver->user->id,
//                    'home_address' => $driver->home_address,
//                    'first_name' => $driver->user->first_name,
//                    'last_name' => $driver->user->last_name,
//                    'license_type' => $driver->license->license_type,
//                    'last_trip_date' => $driver->date_of_last_trip,
//                ],
//                'vehicle' => [
//                    [
//                        'id' => $driver->vehicles[0]->id,
//                        'license_plate_number' => $driver->vehicles[0]->license_plate_number,
//                        'vehicle_make' => $driver->vehicles[0]->vehicle_make,
//                        'vehicle_model' => $driver->vehicles[0]->vehicle_model,
//                        'year' => $driver->vehicles[0]->model_year,
//                        'insured' => $driver->vehicles[0]->insured,
//                        'service_date' => $driver->vehicles[0]->date_of_last_service,
//                        'capacity' => $driver->vehicles[0]->passenger_capacity,
//                    ],
//                ],
//            ]
//        ]);
//
//        $response->assertJsonStructure([
//            [
//                'status',
//                'success',
//                'message',
//                'data' => [
//                    'id',
//                    'id_number',
//                    'phone_number',
//                    'details' => [
//                        'id',
//                        'home_address',
//                        'first_name',
//                        'last_name',
//                        'license_type',
//                        'last_trip_date',
//                    ],
//                    'vehicle' => [
//                        [
//                            'id',
//                            'license_plate_number',
//                            'vehicle_make',
//                            'vehicle_model',
//                            'year',
//                            'insured',
//                            'service_date',
//                            'capacity',
//                        ],
//                    ],
//                ],
//            ]
//        ]);
//    }
}
