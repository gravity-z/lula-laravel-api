<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_number' => $this->faker->unique()->regexify('[0-9]{13}'),
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id;
            },
            'license_id' => function () {
                return \App\Models\License::inRandomOrder()->first()->id;
            },
            'home_address' => $this->faker->address(),
            'date_of_last_trip' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
