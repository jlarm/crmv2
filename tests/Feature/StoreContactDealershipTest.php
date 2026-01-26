<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('a store can be created', function () {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create(['user_id' => $user->id]);

    $store = Store::factory()->create([
        'user_id' => $user->id,
        'dealership_id' => $dealership->id,
        'name' => 'Test Store',
    ]);

    expect($store)->toBeInstanceOf(Store::class)
        ->and($store->name)->toBe('Test Store')
        ->and($store->dealership_id)->toBe($dealership->id)
        ->and($store->user_id)->toBe($user->id);

    $this->assertDatabaseHas('stores', [
        'id' => $store->id,
        'name' => 'Test Store',
    ]);
});

test('a contact can be created', function () {
    $dealership = Dealership::factory()->create();

    $contact = Contact::factory()->create([
        'dealership_id' => $dealership->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect($contact)->toBeInstanceOf(Contact::class)
        ->and($contact->name)->toBe('John Doe')
        ->and($contact->email)->toBe('john@example.com')
        ->and($contact->dealership_id)->toBe($dealership->id);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

test('a store can be soft deleted', function () {
    $store = Store::factory()->create();

    $store->delete();

    expect($store->trashed())->toBeTrue();

    $this->assertSoftDeleted('stores', ['id' => $store->id]);
    $this->assertDatabaseHas('stores', ['id' => $store->id]);
});

test('a soft deleted store can be restored', function () {
    $store = Store::factory()->create();
    $store->delete();

    $store->restore();

    expect($store->trashed())->toBeFalse();
    expect(Store::find($store->id))->not->toBeNull();
});

test('a contact can be soft deleted', function () {
    $contact = Contact::factory()->create();

    $contact->delete();

    expect($contact->trashed())->toBeTrue();

    $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
});

test('a soft deleted contact can be restored', function () {
    $contact = Contact::factory()->create();
    $contact->delete();

    $contact->restore();

    expect($contact->trashed())->toBeFalse();
    expect(Contact::find($contact->id))->not->toBeNull();
});

test('a dealership can be soft deleted', function () {
    $dealership = Dealership::factory()->create();

    $dealership->delete();

    expect($dealership->trashed())->toBeTrue();

    $this->assertSoftDeleted('dealerships', ['id' => $dealership->id]);
    $this->assertDatabaseHas('dealerships', ['id' => $dealership->id]);
});

test('a soft deleted dealership can be restored', function () {
    $dealership = Dealership::factory()->create();
    $dealership->delete();

    $dealership->restore();

    expect($dealership->trashed())->toBeFalse();
    expect(Dealership::find($dealership->id))->not->toBeNull();
});

test('soft deleted stores are excluded from default queries', function () {
    $activeStore = Store::factory()->create();
    $deletedStore = Store::factory()->create();
    $deletedStore->delete();

    $stores = Store::all();

    expect($stores)->toHaveCount(1)
        ->and($stores->first()->id)->toBe($activeStore->id);
});

test('soft deleted contacts are excluded from default queries', function () {
    $activeContact = Contact::factory()->create();
    $deletedContact = Contact::factory()->create();
    $deletedContact->delete();

    $contacts = Contact::all();

    expect($contacts)->toHaveCount(1)
        ->and($contacts->first()->id)->toBe($activeContact->id);
});

test('soft deleted dealerships are excluded from default queries', function () {
    $activeDealership = Dealership::factory()->create();
    $deletedDealership = Dealership::factory()->create();
    $deletedDealership->delete();

    $dealerships = Dealership::all();

    expect($dealerships)->toHaveCount(1)
        ->and($dealerships->first()->id)->toBe($activeDealership->id);
});

test('withTrashed includes soft deleted records', function () {
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->create(['dealership_id' => $dealership->id]);
    $contact = Contact::factory()->create(['dealership_id' => $dealership->id]);

    $store->delete();
    $contact->delete();
    $dealership->delete();

    expect(Store::withTrashed()->count())->toBe(1)
        ->and(Contact::withTrashed()->count())->toBe(1)
        ->and(Dealership::withTrashed()->count())->toBe(1);
});
