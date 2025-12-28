<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Attachable extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdf_attachment_id',
        'attachable_id',
        'attachable_type',
    ];

    public function pdfAttachment(): BelongsTo
    {
        return $this->belongsTo(PdfAttachment::class);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
