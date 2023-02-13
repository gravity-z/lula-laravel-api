<?php

namespace Tests\Feature\VehiclesEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteVehicleIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the DELETE /vehicles/{id} endpoint success status code.
     *
     * @return void
     */
    public function test_delete_vehicle_status_code_success(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create(['driver_id' => $driver->id]);

        // Act
        $response = $this->delete("api/vehicles/{$vehicle->id}");

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the DELETE /vehicles/{id} endpoint failure status code.
     *
     * @return void
     */
    public function test_delete_vehicle_status_code_failure(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create(['driver_id' => $driver->id]);

        // Act
        $response = $this->delete("api/vehicle/{$vehicle->id}");

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the DELETE /vehicles/{id} endpoint.
     * Delete a vehicle with vehicle found
     *
     * @return void
     */
    public function test_delete_vehicle_with_vehicle_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create(['driver_id' => $driver->id]);

        // Act
        $response = $this->delete("api/vehicles/{$vehicle->id}");
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseCount('vehicles', 0);
        $this->assertModelMissing($vehicle);

        $response->assertJson([
            [
                'status' => 'OK',
                'success' => true,
                'message' => 'Vehicle deleted!',
            ]
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

        $response->assertJsonStructure([
            [
                'status',
                'success',
                'message',
            ]
        ]);
    }

    /**
     * Test the DELETE /vehicles/{id} endpoint.
     * Delete a vehicle with vehicle not found
     *
     * @return void
     */
    public function test_delete_vehicle_with_vehicle_not_found(): void
    {
        // Arrange
        User::factory()->create();
        License::factory()->create();
        Driver::factory()->create();

        // Act
        $response = $this->delete('api/vehicles/1');
        $data = $response->json();

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseCount('vehicles', 0);

        $response->assertJson([
            [
                'status' => 'ERROR',
                'success' => false,
                'message' => 'Vehicle could not be deleted.',
            ]
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
