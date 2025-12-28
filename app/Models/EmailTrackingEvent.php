<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_email_id',
        'event_type',
        'message_id',
        'recipient_email',
        'url',
        'user_agent',
        'ip_address',
        'mailgun_data',
        'event_timestamp',
    ];

    protected function casts(): array
    {
        return [
            'mailgun_data' => 'array',
            'event_timestamp' => 'datetime',
        ];
    }

    public function sentEmail(): BelongsTo
    {
        return $this->belongsTo(SentEmail::class);
    }
}
