<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidates';

    protected $fillable = [
        'event_id',
        'name_candidates',
        'number',
        'vision',
        'mission',
        'image',
        'position',
    ];

    /**
     * Public URL for the candidate photo.
     *
     * Three conventions coexist in this column: absolute URLs, paths already
     * rooted in public/ (written by the seeders), and paths relative to the
     * "public" storage disk (written by uploads from the admin panel).
     */
    public function imageUrl(): ?string
    {
        $image = trim((string) $this->image);

        if ($image === '') {
            return null;
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        if (str_starts_with($image, '/')) {
            return $image;
        }

        return Storage::disk('public')->url($image);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'candidate_id', 'id');
    }
}
