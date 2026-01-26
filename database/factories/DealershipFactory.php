<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dealership>
 */
final class DealershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => \App\Models\Organization::factory(),
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'current_solution_name' => fake()->word(),
            'current_solution_use' => fake()->word(),
            'notes' => fake()->paragraph(),
            'status' => fake()->randomElement(['active', 'inactive', 'imported']),
            'rating' => fake()->randomElement(['hot', 'warm', 'cold']),
            'type' => fake()->word(),
            'in_development' => fake()->boolean(),
            'dev_status' => fake()->word(),
        ];
    }
}
