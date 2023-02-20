<?php

namespace Tests\Feature\DriversEndpoint;

use App\Models\Driver;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriversAddressFilterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the GET /drivers endpoint success status code.
     *
     * @return void
     */
    public function test_get_drivers_filter_address_status_code_success(): void
    {
        // Arrange
        $addresses = [
            '31 Loop St, Lula, Cape Town, 8001',
            '35 Loop St, Lula, Cape Town, 8001',
            '32 Kloof St, Gardens, Cape Town, 8001',
            '36 Kloof St, Gardens, Cape Town, 8001',
            '14 Loop St, Lula, Cape Town, 8001',
        ];
        $users = User::factory(5)->create();
        License::factory(5)->create();
        $users->each(function ($user, $index) use ($addresses) {
            Driver::factory()->create([
                'user_id' => $user->id,
                'home_address' => $addresses[$index],
            ]);
        });

        // Act
        $response = $this->get('api/drivers?address=Lula');

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test the GET /drivers endpoint failure status code.
     *
     * @return void
     */
    public function test_get_drivers_filter_address_status_code_failure(): void
    {
        // Arrange
        $addresses = [
            '31 Loop St, Lula, Cape Town, 8001',
            '35 Loop St, Lula, Cape Town, 8001',
            '32 Kloof St, Gardens, Cape Town, 8001',
            '36 Kloof St, Gardens, Cape Town, 8001',
            '14 Loop St, Lula, Cape Town, 8001',
        ];
        $users = User::factory(5)->create();
        License::factory(5)->create();
        $users->each(function ($user, $index) use ($addresses) {
            Driver::factory()->create([
                'user_id' => $user->id,
                'home_address' => $addresses[$index],
            ]);
        });

        // Act
        $response = $this->get('api/drivers?address=Parklands');

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test the GET /drivers endpoint.
     * Drivers found with address filter.
     *
     * @return void
     */
    public function test_get_drivers_filter_address_success(): void
    {
        // Arrange
        $addresses = [
            '31 Loop St, Lula, Cape Town, 8001',
            '35 Loop St, Lula, Cape Town, 8001',
            '32 Kloof St, Gardens, Cape Town, 8001',
            '36 Kloof St, Gardens, Cape Town, 8001',
            '14 Loop St, Lula, Cape Town, 8001',
        ];
        $users = User::factory(5)->create();
        License::factory(5)->create();
        $users->each(function ($user, $index) use ($addresses) {
            Driver::factory()->create([
                'user_id' => $user->id,
                'home_address' => $addresses[$index],
            ]);
        });

        // Act
        $response = $this->get('api/drivers?address=Lula');
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
}
