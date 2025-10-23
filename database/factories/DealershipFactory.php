<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dealership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dealership>
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
            'user_id' => User::factory(),
            'name' => fake()->company().' Auto',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->regexify('[A-Z]{2}'),
            'zip_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'current_solution_name' => fake()->randomElement(['DealerTrack', 'AutoManager', 'VinSolutions', 'CDK Global', 'Reynolds & Reynolds', 'None']),
            'current_solution_use' => fake()->randomElement(['Daily', 'Weekly', 'Monthly', 'Rarely', 'Not Currently']),
            'notes' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['Lead', 'Contacted', 'Demo Scheduled', 'Proposal Sent', 'Negotiating', 'Closed Won', 'Closed Lost']),
            'rating' => fake()->randomElement(['Hot', 'Warm', 'Cold']),
            'type' => fake()->randomElement(['New', 'Used', 'Both']),
            'in_development' => fake()->boolean(30),
            'dev_status' => fake()->randomElement(['Planning', 'In Progress', 'Testing', 'Completed', 'On Hold', 'Not Started']),
        ];
    }
}
