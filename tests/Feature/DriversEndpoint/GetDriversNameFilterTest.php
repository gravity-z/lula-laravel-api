<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriversNameFilterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the GET /drivers endpoint success status code.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_status_code_success(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Donjohn'],
            ['first_name' => 'Johnie'],
            ['last_name' => 'Johnson'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name=John');

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the GET /drivers endpoint failure status code.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_status_code_failure(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Naruto'],
            ['first_name' => 'Sasuke'],
            ['last_name' => 'Uzumaki'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name=John');

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the GET /drivers endpoint.
     * Drivers found with name filter.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_success(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Donjohn'],
            ['first_name' => 'Johnie'],
            ['last_name' => 'Johnson'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name=John');
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseCount('drivers', 5);
        $this->assertCount(3, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Drivers found!',
            ]
        ]);

        $response->assertJsonFragment([
            'id' => $users[0]->driver->id,
            'id_number' => $users[0]->driver->id_number,
            'phone_number' => $users[0]->phone_number,
            'details' => [
                'id' => $users[0]->id,
                'home_address' => $users[0]->driver->home_address,
                'first_name' => $users[0]->first_name,
                'last_name' => $users[0]->last_name,
                'license_type' => $users[0]->driver->license->license_type,
                'last_trip_date' => $users[0]->driver->date_of_last_trip,
            ],
            'vehicle' => [],
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
                        'vehicle',
                    ],
                ]
            ]
        ]);
    }

    /**
     * Test the GET /drivers endpoint.
     * Drivers not found with name filter.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_failure(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Naruto'],
            ['first_name' => 'Sasuke'],
            ['last_name' => 'Uzumaki'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name=John');
        $data = $response->json();

        // Assert
        $response->assertStatus(404);
        $this->assertDatabaseCount('drivers', 5);
        $this->assertCount(0, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Drivers not found!',
            ]
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
     * Test the GET /drivers endpoint.
     * Drivers not found with name filter too long.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_too_long(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Donjohn'],
            ['first_name' => 'Johnie'],
            ['last_name' => 'Johnson'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name=johndhshdjl');
        $data = $response->json();

        // Assert
        $response->assertStatus(422);
        $this->assertDatabaseCount('drivers', 5);
        $this->assertCount(0, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'The name must not be greater than 10 characters.',
            ]
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
     * Test the GET /drivers endpoint.
     * Drivers not found with name filter not string.
     *
     * @return void
     */
    public function test_get_drivers_filter_name_not_string(): void
    {
        // Arrange
        $users = User::factory()->createMany([
            ['first_name' => 'Donjohn'],
            ['first_name' => 'Johnie'],
            ['last_name' => 'Johnson'],
            ['first_name' => 'George'],
            ['first_name' => 'Paul'],
        ]);
        License::factory(5)->create();
        $users->each(function ($user) {
            Driver::factory()->create(['user_id' => $user->id]);
        });

        // Act
        $response = $this->get('api/drivers?name= ');
        $data = $response->json();

        // Assert
        $response->assertStatus(422);
        $this->assertDatabaseCount('drivers', 5);
        $this->assertCount(0, $data[0]['data']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'The name must be a string.',
            ]
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
