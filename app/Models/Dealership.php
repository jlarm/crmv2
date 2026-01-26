<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DealershipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Number;

final class Dealership extends Model
{
    use HasFactory;

    /** @use HasFactory<DealershipFactory> */
    use SoftDeletes;

    protected $fillable = [
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
    ];

    public static function activeStatusCount(): false|string
    {
        return Number::format(self::where('status', 'active')->count());
    }

    public static function inactiveStatusCount(): false|string
    {
        return Number::format(self::where('status', 'inactive')->count());
    }

    public static function importedStatusCount(): false|string
    {
        return Number::format(self::where('status', 'imported')->count());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function dealerEmails(): HasMany
    {
        return $this->hasMany(DealerEmail::class);
    }

    public function sentEmails(): HasMany
    {
        return $this->hasMany(SentEmail::class);
    }

    protected function casts(): array
    {
        return [
            'in_development' => 'boolean',
        ];
    }
}
