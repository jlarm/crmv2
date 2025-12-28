<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dealership_id',
        'recipient',
        'message_id',
        'subject',
        'tracking_data',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }

    public function emailTrackingEvents(): HasMany
    {
        return $this->hasMany(EmailTrackingEvent::class);
    }

    protected function casts(): array
    {
        return [
            'tracking_data' => 'array',
        ];
    }
}
