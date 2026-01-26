<?php

declare(strict_types=1);

use App\Livewire\OrganizationSwitcher;
use App\Models\Dealership;
use App\Models\Organization;
use App\Models\User;
use Livewire\Livewire;

test('dealerships are scoped by organization', function () {
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    $dealership1 = Dealership::factory()->create(['organization_id' => $org1->id]);
    $dealership2 = Dealership::factory()->create(['organization_id' => $org2->id]);

    session(['current_organization_id' => $org1->id]);

    $dealerships = Dealership::all();

    expect($dealerships)->toHaveCount(1)
        ->and($dealerships->first()->id)->toBe($dealership1->id);
});

test('user can switch organizations', function () {
    $user = User::factory()->create();
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    $user->organizations()->attach([$org1->id, $org2->id]);

    session(['current_organization_id' => $org1->id]);

    Livewire::actingAs($user)
        ->test(OrganizationSwitcher::class)
        ->call('switchOrganization', $org2->id);

    expect(session('current_organization_id'))->toBe($org2->id);
});

test('user cannot switch to unauthorized organization', function () {
    $user = User::factory()->create();
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    $user->organizations()->attach($org1->id);

    session(['current_organization_id' => $org1->id]);

    Livewire::actingAs($user)
        ->test(OrganizationSwitcher::class)
        ->call('switchOrganization', $org2->id);

    expect(session('current_organization_id'))->toBe($org1->id);
});

test('middleware sets default organization', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();

    $user->organizations()->attach($organization->id);

    $this->actingAs($user)
        ->get('/dashboard');

    expect(session('current_organization_id'))->toBe($organization->id);
});

test('user belongs to organization validation', function () {
    $user = User::factory()->create();
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    $user->organizations()->attach($org1->id);

    expect($user->belongsToOrganization($org1->id))->toBeTrue()
        ->and($user->belongsToOrganization($org2->id))->toBeFalse();
});

test('current organization is retrieved from session', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();

    $user->organizations()->attach($organization->id);

    session(['current_organization_id' => $organization->id]);

    expect($user->currentOrganization())->not->toBeNull()
        ->and($user->currentOrganization()->id)->toBe($organization->id);
});

test('organization has many dealerships', function () {
    $organization = Organization::factory()->create();
    $dealership = Dealership::factory()->create(['organization_id' => $organization->id]);

    session(['current_organization_id' => $organization->id]);

    expect($organization->dealerships)->toHaveCount(1)
        ->and($organization->dealerships->first()->id)->toBe($dealership->id);
});

test('dealership belongs to organization', function () {
    $organization = Organization::factory()->create();
    $dealership = Dealership::factory()->create(['organization_id' => $organization->id]);

    expect($dealership->organization)->not->toBeNull()
        ->and($dealership->organization->id)->toBe($organization->id);
});

test('user can create a new organization', function () {
    $user = User::factory()->create();
    $existingOrg = Organization::factory()->create();
    $user->organizations()->attach($existingOrg->id);

    session(['current_organization_id' => $existingOrg->id]);

    Livewire::actingAs($user)
        ->test(OrganizationSwitcher::class)
        ->set('name', 'New Organization')
        ->call('createOrganization');

    $newOrg = Organization::where('name', 'New Organization')->first();

    expect($newOrg)->not->toBeNull()
        ->and($newOrg->slug)->toBe('new-organization')
        ->and($user->fresh()->organizations)->toHaveCount(2)
        ->and(session('current_organization_id'))->toBe($newOrg->id);
});

test('organization name is required when creating', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(OrganizationSwitcher::class)
        ->set('name', '')
        ->call('createOrganization')
        ->assertHasErrors('name');
});

test('duplicate organization slugs are handled', function () {
    $user = User::factory()->create();
    Organization::factory()->create(['name' => 'Test Org', 'slug' => 'test-org']);

    Livewire::actingAs($user)
        ->test(OrganizationSwitcher::class)
        ->set('name', 'Test Org')
        ->call('createOrganization');

    $newOrg = Organization::where('slug', 'test-org-1')->first();

    expect($newOrg)->not->toBeNull()
        ->and($newOrg->name)->toBe('Test Org');
});
