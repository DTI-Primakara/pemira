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
        'showcase_image',
        'is_blank_box',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'is_blank_box' => 'boolean',
        ];
    }

    /** Public URL for the plain photo used on the ballot. */
    public function imageUrl(): ?string
    {
        return $this->resolveImageUrl($this->image);
    }

    /**
     * Public URL for the finished artwork shown in the homepage showcase,
     * falling back to the ballot photo while none has been uploaded.
     */
    public function showcaseImageUrl(): ?string
    {
        return $this->resolveImageUrl($this->showcase_image) ?? $this->imageUrl();
    }

    /**
     * The homepage artwork and nothing else — null when none was uploaded.
     *
     * Separate from showcaseImageUrl() because its fallback to the ballot
     * photo is right for the candidate list but wrong for the hero, where a
     * bare portrait among designed cards reads as a mistake.
     */
    public function showcaseArtworkUrl(): ?string
    {
        return $this->resolveImageUrl($this->showcase_image);
    }

    /**
     * Three conventions coexist in these columns: absolute URLs, paths already
     * rooted in public/ (written by the seeders), and paths relative to the
     * "public" storage disk (written by uploads from the admin panel).
     */
    private function resolveImageUrl(?string $value): ?string
    {
        $image = trim((string) $value);

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
