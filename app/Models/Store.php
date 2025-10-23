<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $dealership_id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $current_solution_name
 * @property string $current_solution_use
 * @property string $notes
 */
final class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'dealership_id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zip_code' => 'string',
        'phone' => 'string',
        'current_solution_name' => 'string',
        'current_solution_use' => 'string',
        'notes' => 'string',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Dealership, $this>
     */
    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }
}
