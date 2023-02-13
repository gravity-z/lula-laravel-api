<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PutDriverIdDetailsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the PUT /drivers/{id}/details endpoint success status code.
     *
     * @return void
     */
    public function test_put_driver_details_status_code_success(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'home_address' => $this->faker->address,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'licence_type' => 'A',
            'last_trip_date' => '2023-01-14T00:00:00.000000Z',
        ];

        // Act
        $response = $this->put("api/drivers/{$driver->id}/details", $payload);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the PUT /drivers/{id}/details endpoint failure status code.
     *
     * @return void
     */
    public function test_put_driver_details_status_code_failure(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'home_address' => $this->faker->address,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'licence_type' => 'E',
            'last_trip_date' => '2023-01-14T00:00:00.000000Z',
        ];

        // Act
        $response = $this->put("api/drivers/{$driver->id}/details", $payload);

        // Assert
        $response->assertStatus(400);
    }

    /**
     * Test the PUT /drivers/{id}/details endpoint.
     * Update driver details.
     * Newly updated driver details should be returned.
     *
     * @return void
     */
    public function test_put_driver_details_with_driver_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'home_address' => $this->faker->address,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'licence_type' => 'A',
            'last_trip_date' => '2023-01-14T00:00:00.000000Z',
        ];

        // Act
        $response = $this->put("api/drivers/{$driver->id}/details", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($payload['home_address'], $data[0]['data']['home_address']);
        $this->assertEquals($payload['first_name'], $data[0]['data']['first_name']);
        $this->assertEquals($payload['last_name'], $data[0]['data']['last_name']);
        $this->assertEquals($data[0]['data']['licence_type'], $data[0]['data']['licence_type']);
        $this->assertEquals($payload['last_trip_date'], $data[0]['data']['last_trip_date']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver information updated!',
            ]
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'home_address' => $payload['home_address'],
            'date_of_last_trip' => $payload['last_trip_date'],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $driver->user_id,
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
        ]);

        $this->assertDatabaseHas('licenses', [
            'id' => $driver->license_id,
            'license_type' => $data[0]['data']['licence_type'],
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'home_address' => $payload['home_address'],
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
            'licence_type' => $data[0]['data']['licence_type'],
            'last_trip_date' => $payload['last_trip_date'],
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
                'data' => [
                    'id',
                    'home_address',
                    'first_name',
                    'last_name',
                    'licence_type',
                    'last_trip_date',
                ],
            ]
        ]);
    }
}
