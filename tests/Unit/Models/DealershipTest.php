<?php

declare(strict_types=1);

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $dealership = Dealership::factory()->create()->refresh();

    expect(array_keys($dealership->toArray()))
        ->toBe([
            'id',
            'user_id',
            'name',
            'address',
            'city',
            'state',
            'zip_code',
            'phone',
            'email',
            'current_solution_name',
            'current_solution_use',
            'notes',
            'status',
            'rating',
            'type',
            'in_development',
            'dev_status',
            'created_at',
            'updated_at',
        ]);
});

test('belongs to user', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create(['user_id' => $user->id]);

    expect($dealership->user)
        ->toBeInstanceOf(User::class)
        ->id->toBe($user->id);
});

test('belongs to many users', function (): void {
    $dealership = Dealership::factory()->create();
    $users = User::factory(3)->create();

    $dealership->users()->attach($users->pluck('id'));

    expect($dealership->users)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(User::class);
});

test('has many stores', function (): void {
    $dealership = Dealership::factory()->create();
    Store::factory()->create(['dealership_id' => $dealership->id]);
    Store::factory()->create(['dealership_id' => $dealership->id]);

    expect($dealership->stores)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Store::class);
});
