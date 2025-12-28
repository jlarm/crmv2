<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Progress extends Model
{
    /** @use HasFactory<\Database\Factories\ProgressFactory> */
    use HasFactory;

    protected $table = 'progresses';

    protected $fillable = [
        'user_id',
        'dealership_id',
        'contact_id',
        'progress_category_id',
        'details',
        'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function progressCategory(): BelongsTo
    {
        return $this->belongsTo(ProgressCategory::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
