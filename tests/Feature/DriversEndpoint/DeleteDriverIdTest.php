<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteDriverIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the DELETE /drivers/{id} endpoint success status code.
     *
     * @return void
     */
    public function test_delete_driver_status_code_success(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        Vehicle::factory()->create();

        // Act
        $response = $this->delete("api/drivers/{$driver->id}");

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the DELETE /drivers/{id} endpoint failure status code.
     *
     * @return void
     */
    public function test_delete_driver_status_code_failure(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        Vehicle::factory()->create();

        // Act
        $response = $this->delete("api/driver/{$driver->id}");

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the DELETE /drivers/{id} endpoint.
     * Newly deleted driver account should be missing in the database.
     *
     * @return void
     */
    public function test_delete_driver_with_driver_deleted(): void
    {
        // Arrange
        $user = User::factory()->create();
        $license = License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        // Act
        $response = $this->delete("api/drivers/{$driver->id}");
        $driverId = Driver::find($driver->id);

        // Assert
        $response->assertStatus(200);
        $this->assertNull($driverId);
        $this->assertDeleted($user);
        $this->assertDeleted($driver);
        $this->assertDeleted($license);
        $this->assertDeleted($vehicle);
        $this->assertDatabaseCount('drivers', 0);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Driver account deleted!',
            ]
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'password' => $user->password,
        ]);

        $this->assertDatabaseMissing('drivers', [
            'id' => $driver->id,
            'id_number' => $driver->id_number,
            'user_id' => $user->id,
            'license_id' => $license->id,
            'home_address' => $driver->home_address,
            'date_of_last_trip' => $driver->date_of_last_trip,
        ]);

        $this->assertDatabaseMissing('licenses', [
            'id' => $license->id,
            'license_type' => $license->licence_type,
        ]);

        $this->assertDatabaseMissing('vehicles', [
            'license_plate_number' => $vehicle->license_plate_number,
            'vehicle_make' => $vehicle->vehicle_make,
            'vehicle_model' => $vehicle->vehicle_model,
            'model_year' => $vehicle->model_year,
            'insured' => $vehicle->insured,
            'date_of_last_service' => $vehicle->date_of_last_service,
            'passenger_capacity' => $vehicle->passenger_capacity,
            'driver_id' => $vehicle->driver_id,
        ]);

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
            ]
        ]);
    }
}
