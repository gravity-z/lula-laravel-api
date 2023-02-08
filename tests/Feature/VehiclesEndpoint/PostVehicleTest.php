<?php

namespace Tests\Feature\VehiclesEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostVehicleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the POST /vehicles endpoint success status code.
     *
     * @return void
     */
    public function test_put_vehicle_status_code_success(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'licence_plate_number' => $this->faker->unique()->regexify('[A-Z]{2} [0-9]{2} [A-Z]{2}'),
            'vehicle_make' => $this->faker->randomElement(['Toyota', 'Ford', 'BMW', 'Mercedes']),
            'vehicle_model' => $this->faker->randomElement(['Camry', 'Fiesta', 'X5', 'C-Class']),
            'year' => $this->faker->year($max = 'now'),
            'insured' => $this->faker->boolean,
            'service_date' => $this->faker->dateTimeThisYear,
            'capacity' => $this->faker->numberBetween($min = 1, $max = 10),
            'driver_id' => $driver->id,
        ];

        // Act
        $response = $this->post('api/vehicles', $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the POST /vehicles endpoint.
     * Create a new vehicle.
     * Newly created vehicle should be returned.
     *
     * @return void
     */
    public function test_post_vehicle_with_driver_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'license_plate_number' => $this->faker->unique()->regexify('[A-Z]{2} [0-9]{2} [A-Z]{2}'),
            'vehicle_make' => $this->faker->randomElement(['Toyota', 'Ford', 'BMW', 'Mercedes']),
            'vehicle_model' => $this->faker->randomElement(['Camry', 'Fiesta', 'X5', 'C-Class']),
            'model_year' => $this->faker->year($max = 'now'),
            'insured' => $this->faker->boolean,
            'date_of_last_service' => '2023-01-14T00:00:00.000000Z',
            'passenger_capacity' => $this->faker->numberBetween($min = 1, $max = 10),
            'driver_id' => $driver->id,
        ];

        // Act
        $response = $this->post('api/vehicles', $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($payload['license_plate_number'], $data[0]['data']['license_plate_number']);
        $this->assertEquals($payload['vehicle_make'], $data[0]['data']['vehicle_make']);
        $this->assertEquals($payload['vehicle_model'], $data[0]['data']['vehicle_model']);
        $this->assertEquals($payload['model_year'], $data[0]['data']['year']);
        $this->assertEquals($payload['insured'], $data[0]['data']['insured']);
        $this->assertEquals($payload['date_of_last_service'], $data[0]['data']['service_date']);
        $this->assertEquals($payload['passenger_capacity'], $data[0]['data']['capacity']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Vehicle created!',
            ]
        ]);

        $this->assertDatabaseHas('vehicles', [
            'license_plate_number' => $payload['license_plate_number'],
            'vehicle_make' => $payload['vehicle_make'],
            'vehicle_model' => $payload['vehicle_model'],
            'model_year' => $payload['model_year'],
            'insured' => $payload['insured'],
            'date_of_last_service' => $payload['date_of_last_service'],
            'passenger_capacity' => $payload['passenger_capacity'],
            'driver_id' => $payload['driver_id'],
        ]);

        $response->assertJsonFragment([
            'license_plate_number' => $payload['license_plate_number'],
            'vehicle_make' => $payload['vehicle_make'],
            'vehicle_model' => $payload['vehicle_model'],
            'year' => $data[0]['data']['year'],
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
            ]
        ]);
    }
}
