<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final class DealerEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dealership_id',
        'dealer_email_template_id',
        'customize_email',
        'customize_attachment',
        'recipients',
        'attachment',
        'subject',
        'message',
        'start_date',
        'last_sent',
        'next_send_date',
        'frequency',
        'paused',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }

    public function dealerEmailTemplate(): BelongsTo
    {
        return $this->belongsTo(DealerEmailTemplate::class);
    }

    public function pdfAttachments(): MorphToMany
    {
        return $this->morphToMany(PdfAttachment::class, 'attachable', 'attachables');
    }

    protected function casts(): array
    {
        return [
            'customize_email' => 'boolean',
            'customize_attachment' => 'boolean',
            'recipients' => 'array',
            'start_date' => 'date',
            'last_sent' => 'date',
            'next_send_date' => 'date',
            'paused' => 'boolean',
        ];
    }
}
