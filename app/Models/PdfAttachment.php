<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

final class PdfAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
    ];

    public function attachables(): HasMany
    {
        return $this->hasMany(Attachable::class);
    }

    public function dealerEmails(): MorphedByMany
    {
        return $this->morphedByMany(DealerEmail::class, 'attachable', 'attachables');
    }
}
