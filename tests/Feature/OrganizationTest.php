<?php

declare(strict_types=1);

use App\Models\Dealership;
use App\Models\Organization;
use App\Models\User;

test('an organization can be created', function () {
    $organization = Organization::factory()->create([
        'name' => 'Test Organization',
        'slug' => 'test-organization',
    ]);

    expect($organization)->toBeInstanceOf(Organization::class)
        ->and($organization->name)->toBe('Test Organization')
        ->and($organization->slug)->toBe('test-organization');

    $this->assertDatabaseHas('organizations', [
        'id' => $organization->id,
        'name' => 'Test Organization',
        'slug' => 'test-organization',
    ]);
});

test('organization slug must be unique', function () {
    Organization::factory()->create(['slug' => 'unique-slug']);

    $this->expectException(Illuminate\Database\QueryException::class);

    Organization::factory()->create(['slug' => 'unique-slug']);
});

test('organization has many users', function () {
    $organization = Organization::factory()->create();
    $users = User::factory()->count(3)->create();

    $organization->users()->attach($users->pluck('id'));

    expect($organization->users)->toHaveCount(3);
});

test('organization has many dealerships', function () {
    $organization = Organization::factory()->create();

    session(['current_organization_id' => $organization->id]);

    Dealership::factory()->count(3)->create(['organization_id' => $organization->id]);

    expect($organization->dealerships)->toHaveCount(3);
});

test('organization factory generates unique slug', function () {
    $organizations = Organization::factory()->count(5)->create();

    $slugs = $organizations->pluck('slug')->toArray();

    expect($slugs)->toHaveCount(5)
        ->and(array_unique($slugs))->toHaveCount(5);
});

test('user can belong to multiple organizations', function () {
    $user = User::factory()->create();
    $organizations = Organization::factory()->count(3)->create();

    $user->organizations()->attach($organizations->pluck('id'));

    expect($user->organizations)->toHaveCount(3);
});

test('organization user relationship includes timestamps', function () {
    $organization = Organization::factory()->create();
    $user = User::factory()->create();

    $organization->users()->attach($user->id);

    $pivot = $organization->users()->first()->pivot;

    expect($pivot->created_at)->not->toBeNull()
        ->and($pivot->updated_at)->not->toBeNull();
});

test('deleting organization detaches users', function () {
    $organization = Organization::factory()->create();
    $user = User::factory()->create();

    $user->organizations()->attach($organization->id);

    expect($user->organizations)->toHaveCount(1);

    $organization->delete();

    expect($user->fresh()->organizations)->toHaveCount(0);
});

test('organization name is fillable', function () {
    $organization = new Organization;
    $organization->fill(['name' => 'Fillable Test', 'slug' => 'fillable-test']);

    expect($organization->name)->toBe('Fillable Test')
        ->and($organization->slug)->toBe('fillable-test');
});
