<?php

declare(strict_types=1);

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $store = Store::factory()->create()->refresh();

    expect(array_keys($store->toArray()))
        ->toBe([
            'id',
            'user_id',
            'dealership_id',
            'name',
            'address',
            'city',
            'state',
            'zip_code',
            'phone',
            'current_solution_name',
            'current_solution_use',
            'notes',
            'created_at',
            'updated_at',
        ]);
});

test('belongs to user', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->create([
        'user_id' => $user->id,
        'dealership_id' => $dealership->id,
    ]);

    expect($store->user)
        ->toBeInstanceOf(User::class)
        ->id->toBe($user->id);
});

test('belongs to dealership', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->create([
        'user_id' => $user->id,
        'dealership_id' => $dealership->id,
    ]);

    expect($store->dealership)
        ->toBeInstanceOf(Dealership::class)
        ->id->toBe($dealership->id);
});
