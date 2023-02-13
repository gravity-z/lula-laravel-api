<?php

namespace Tests\Feature\VehiclesEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutVehiclesIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the PUT /vehicles/{id} endpoint success status code.
     *
     * @return void
     */
    public function test_put_vehicles_id_status_code_success(): void
    {
        $response = $this->put('api/vehicles/1');

        $response->assertStatus(200);
    }

    /**
     * Test the PUT /vehicles/{id} endpoint failure status code.
     *
     * @return void
     */
    public function test_put_vehicles_id_status_code_failure(): void
    {
        $response = $this->put('api/vehicle/1');

        $response->assertStatus(404);
    }

    /**
     * Test the PUT /vehicles/{id} endpoint.
     * Update a vehicle with vehicle found
     *
     * @return void
     */
    public function test_put_vehicle_id_with_vehicle_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create(['driver_id' => $driver->id]);
        $payload = [
            "id" => $vehicle->id,
            "license_plate_number" => $this->faker->unique()->regexify('[A-Z]{2} [0-9]{2} [A-Z]{2}'),
            "vehicle_make" => "Mercedes-Benz",
            "vehicle_model" => "G-Class",
            "model_year" => 2020,
            "insured" => $this->faker->boolean,
            "date_of_last_service" => "2022-10-12T00:00:00.000000Z",
            "passenger_capacity" => $this->faker->numberBetween($min = 1, $max = 5),
        ];

        // Act
        $response = $this->put("api/vehicles/{$vehicle->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($payload['id'], $data[0]['data']['id']);

        $response->assertJson([
           [
               'status' => 'OK',
               'success' => true,
               'message' => 'Vehicle details updated.',
           ]
        ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $payload['id'],
            'license_plate_number' => $payload['license_plate_number'],
            'vehicle_make' => $payload['vehicle_make'],
            'vehicle_model' => $payload['vehicle_model'],
            'model_year' => $payload['model_year'],
            'insured' => $payload['insured'],
            'date_of_last_service' => $payload['date_of_last_service'],
            'passenger_capacity' => $payload['passenger_capacity'],
        ]);

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
            'license_plate_number' => $vehicle->license_plate_number,
            'vehicle_make' => $vehicle->vehicle_make,
            'vehicle_model' => $vehicle->vehicle_model,
            'model_year' => $vehicle->model_year,
            'insured' => $vehicle->insured,
            'date_of_last_service' => $vehicle->date_of_last_service,
            'passenger_capacity' => $vehicle->passenger_capacity,
        ]);

        $this->assertModelExists($vehicle);

//        dd($response->json());
        $response->assertJsonFragment([
            'id' => $payload['id'],
            'license_plate_number' => $payload['license_plate_number'],
            'vehicle_make' => $payload['vehicle_make'],
            'vehicle_model' => $payload['vehicle_model'],
            'year' => $payload['model_year'],
            'insured' => $payload['insured'],
            'service_date' => $payload['date_of_last_service'],
            'capacity' => $payload['passenger_capacity'],
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
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
        ]);
    }

    /**
     * Test the PUT /vehicles/{id} endpoint.
     * Update a vehicle with vehicle not found
     *
     * @return void
     */
    public function test_put_vehicle_id_with_vehicle_not_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();

        $payload = [
            "id" => 1,
            "license_plate_number" => $this->faker->unique()->regexify('[A-Z]{2} [0-9]{2} [A-Z]{2}'),
            "vehicle_make" => "Mercedes-Benz",
            "vehicle_model" => "G-Class",
            "model_year" => 2020,
            "insured" => $this->faker->boolean,
            "date_of_last_service" => "2022-10-12T00:00:00.000000Z",
            "passenger_capacity" => $this->faker->numberBetween($min = 1, $max = 5),
        ];

        // Act
        $response = $this->put("api/vehicles/1", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Vehicle details could not be updated.',
            ]
        ]);

        $this->assertDatabaseMissing('vehicles', [
            'id' => $payload['id'],
            'license_plate_number' => $payload['license_plate_number'],
            'vehicle_make' => $payload['vehicle_make'],
            'vehicle_model' => $payload['vehicle_model'],
            'model_year' => $payload['model_year'],
            'insured' => $payload['insured'],
            'date_of_last_service' => $payload['date_of_last_service'],
            'passenger_capacity' => $payload['passenger_capacity'],
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
            ],
        ]);
    }
}
