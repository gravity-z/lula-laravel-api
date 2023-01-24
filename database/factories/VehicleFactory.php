<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MongoDB\BSON\UTCDateTime;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'license_plate_number' => $this->faker->unique()->regexify('[A-Z]{2} [0-9]{2} [A-Z]{2}'),
            'vehicle_make' => $this->faker->randomElement(['Toyota', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Volkswagen', 'Hyundai', 'Kia', 'Lamborghini', 'Ferrari', 'Porsche', 'Aston Martin', 'Land Rover', 'Jaguar', 'Volvo', 'Subaru', 'Maserati', 'Mini', 'Skoda', 'Lexus', 'Mitsubishi', 'Haval', 'Bentley', 'Rolls Royce', 'Bugatti', 'Tesla', 'Koenigsegg', 'Pagani', 'McLaren', 'Hummer', 'Dacia', 'Datsun', 'Isuzu', 'Iveco']),
            'vehicle_model' => $this->faker->randomElement(['Corolla', 'Camry', 'Prius', 'Yaris', 'Auris', 'Avensis', 'Verso', 'Rav4', 'Land Cruiser', 'Hiace', 'Hilux', 'Fortuner', 'Prado', 'C-HR', 'Mirai', 'Crown', 'Century', 'Vellfire', 'Alphard', 'Sienta', 'Vios', 'Altis', 'Wigo', 'Innova', 'Fortuner', 'Hiace', 'Hilux', 'Land Cruiser', 'Prado', 'Vios', 'Altis', 'Wigo', 'Innova', 'Fortuner', 'Hiace', 'Hilux']),
            'model_year' => $this->faker->year($max = 'now'),
            'insured' => $this->faker->boolean,
            'date_of_last_service' => $this->faker->dateTimeThisYear,
            'passenger_capacity' => $this->faker->numberBetween($min = 1, $max = 10),
            'driver_id' => function () {
                return \App\Models\Driver::inRandomOrder()->first()->id;
            },
        ];
    }
}
