<?php

declare(strict_types=1);

use App\Http\Resources\DealershipResource;
use App\Models\Dealership;
use Illuminate\Support\Facades\Request;

test('transforms dealership to array with correct fields', function (): void {
    $dealership = Dealership::factory()->create([
        'name' => 'Test Dealership Auto',
        'city' => 'Indianapolis',
        'state' => 'IN',
        'phone' => '555-1234',
        'status' => 'Lead',
        'rating' => 'Hot',
    ]);

    $resource = new DealershipResource($dealership);
    $request = Request::create('/test');

    $array = $resource->toArray($request);

    expect($array)->toHaveKeys(['id', 'name', 'city', 'state', 'phone', 'status', 'rating'])
        ->and($array['id'])->toBe($dealership->id)
        ->and($array['name'])->toBe('Test Dealership Auto')
        ->and($array['city'])->toBe('Indianapolis')
        ->and($array['state'])->toBe('IN')
        ->and($array['phone'])->toBe('555-1234')
        ->and($array['status'])->toBe('Lead')
        ->and($array['rating'])->toBe('Hot');
});

test('excludes sensitive fields from dealership resource', function (): void {
    $dealership = Dealership::factory()->create();

    $resource = new DealershipResource($dealership);
    $request = Request::create('/test');

    $array = $resource->toArray($request);

    expect($array)->not->toHaveKeys([
        'address',
        'zip_code',
        'email',
        'current_solution_name',
        'current_solution_use',
        'notes',
        'type',
        'in_development',
        'dev_status',
        'user_id',
    ]);
});
