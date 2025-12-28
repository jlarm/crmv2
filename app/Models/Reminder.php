<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dev_rel',
        'title',
        'message',
        'start_date',
        'last_sent',
        'sending_frequency',
        'pause',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'dev_rel' => 'boolean',
            'start_date' => 'date',
            'last_sent' => 'date',
            'pause' => 'boolean',
        ];
    }
}
