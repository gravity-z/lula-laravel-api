<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatchDriverIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the PATCH /drivers/{id} endpoint success status code.
     *
     * @return void
     */
    public function test_patch_driver_status_code_success(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 1234567890235,
            'phone_number' => 1203546879,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint failure status code.
     *
     * @return void
     */
    public function test_patch_driver_status_code_failure(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 1234567890235,
            'phone_number' => 1203546879,
        ];

        // Act
        $response = $this->patch("api/driver/{$driver->id}", $payload);

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating id_number and phone_number.
     * Newly updated id_number and phone_number are returned.
     *
     * @return void
     */
    public function test_patch_driver_with_driver_found_with_updated_id_number_and_phone_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 1234567890235,
            'phone_number' => 1203546879,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertEquals($payload['id_number'], $data[0]['data']['id_number']);
        $this->assertEquals($payload['phone_number'], $data[0]['data']['phone_number']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver account updated!'
            ]
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'id_number' => $payload['id_number']
        ]);
        $this->assertDatabaseHas('users', [
            'phone_number' => $payload['phone_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $payload['id_number'],
            'phone_number' => $payload['phone_number']
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
                ]
            ]
        ]);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating id_number only
     * Newly updated id_number is found in the response.
     *
     * @return void
     */

    public function test_patch_driver_with_driver_found_with_updated_id_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 1234567890235,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertEquals($payload['id_number'], $data[0]['data']['id_number']);
        $this->assertEquals($driver->user->phone_number, $data[0]['data']['phone_number']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver account updated!'
            ]
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'id_number' => $payload['id_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $payload['id_number'],
            'phone_number' => $driver->user->phone_number
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
                ]
            ]
        ]);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating phone_number only
     * Newly updated phone_number is found in the response.
     *
     * @return void
     */
    public function test_patch_driver_with_driver_found_with_updated_phone_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'phone_number' => 1203546879,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertEquals($payload['phone_number'], $data[0]['data']['phone_number']);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver account updated!'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'phone_number' => $payload['phone_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $driver->id_number,
            'phone_number' => $payload['phone_number']
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
                ]
            ]
        ]);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating phone_number and id_number
     * Error updating phone_number and id_number, old data found in the response.
     *
     * @return void
     */
    public function test_patch_driver_with_driver_found_without_updated_phone_number_and_id_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 123456789023,
            'phone_number' => 1203546879,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertNotEquals($payload['id_number'], $data[0]['data']['id_number']);
        $this->assertNotEquals($payload['phone_number'], $data[0]['data']['phone_number']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Driver account could not be updated!'
            ]
        ]);

        $this->assertDatabaseMissing('drivers', [
            'id_number' => $payload['id_number']
        ]);

        $this->assertDatabaseMissing('users', [
            'phone_number' => $payload['phone_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $driver->id_number,
            'phone_number' => $driver->user->phone_number
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
                ]
            ]
        ]);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating id_number
     * Error updating id_number, old data found in the response.
     *
     * @return void
     */
    public function test_patch_driver_with_driver_found_without_updated_id_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'id_number' => 12345678902344,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertNotEquals($payload['id_number'], $data[0]['data']['id_number']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Driver account could not be updated!'
            ]
        ]);

        $this->assertDatabaseMissing('drivers', [
            'id_number' => $payload['id_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $driver->id_number,
            'phone_number' => $driver->user->phone_number
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
                ]
            ]
        ]);
    }

    /**
     * Test the PATCH /drivers/{id} endpoint.
     * Updating phone_number
     * Error updating phone_number, old data found in the response.
     *
     * @return void
     */
    public function test_patch_driver_with_driver_found_without_updated_phone_number(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $payload = [
            'phone_number' => 12035468795,
        ];

        // Act
        $response = $this->patch("api/drivers/{$driver->id}", $payload);
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($driver->id, $data[0]['data']['id']);
        $this->assertNotEquals($payload['phone_number'], $data[0]['data']['phone_number']);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Driver account could not be updated!'
            ]
        ]);

        $this->assertDatabaseMissing('users', [
            'phone_number' => $payload['phone_number']
        ]);

        $response->assertJsonFragment([
            'id' => $driver->id,
            'id_number' => $driver->id_number,
            'phone_number' => $driver->user->phone_number
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
                ]
            ]
        ]);
    }
}
