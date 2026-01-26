<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Dealership;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

final class MigrateExistingDataToOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::firstOrCreate(
            ['slug' => 'armp'],
            ['name' => 'armp']
        );

        User::query()
            ->whereDoesntHave('organizations')
            ->chunk(100, function ($users) use ($organization): void {
                foreach ($users as $user) {
                    $user->organizations()->attach($organization->id);
                }
            });

        Dealership::withoutGlobalScopes()
            ->whereNull('organization_id')
            ->update(['organization_id' => $organization->id]);
    }
}
