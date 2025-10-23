<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DealershipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $email
 * @property string $current_solution_name
 * @property string $current_solution_use
 * @property string $notes
 * @property string $status
 * @property string $rating
 * @property string $type
 * @property bool $in_development
 * @property string $dev_status
 */
final class Dealership extends Model
{
    /** @use HasFactory<DealershipFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'name' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip_code' => 'string',
            'phone' => 'string',
            'email' => 'string',
            'current_solution_name' => 'string',
            'current_solution_use' => 'string',
            'notes' => 'string',
            'status' => 'string',
            'rating' => 'string',
            'type' => 'string',
            'in_development' => 'boolean',
            'dev_status' => 'string',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return HasMany<Store, $this>
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
