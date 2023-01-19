<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'license_type' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
