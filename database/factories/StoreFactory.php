<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
final class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'dealership_id' => Dealership::factory(),
            'name' => fake()->company().' Auto',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->regexify('[A-Z]{2}'),
            'zip_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'current_solution_name' => fake()->randomElement(['DealerTrack', 'AutoManager', 'VinSolutions', 'CDK Global', 'Reynolds & Reynolds', 'None']),
            'current_solution_use' => fake()->randomElement(['Daily', 'Weekly', 'Monthly', 'Rarely', 'Not Currently']),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
