<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => fake()->randomElement(['Dell Laptop', 'HP Desktop', 'iPad Pro', 'iPhone 14']),
            'serial_number' => fake()->unique()->bothify('SN-####'),
            'status'        => 'available',
        ];
    }
}
