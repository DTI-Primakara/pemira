<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Config extends Model
{
    protected $table = 'config';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'value',
    ];

    public const CACHE_KEY = 'pemira.config';

    public const VOTE_COUNT_VISIBLE = 'is_vote_count_visible';

    public const VOTING_STARTS_AT = 'voting_starts_at';

    public const VOTING_ENDS_AT = 'voting_ends_at';

    public const TIMEZONE = 'Asia/Makassar';

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * All settings as a title => value map, cached for the request lifetime.
     *
     * @return array<string, string>
     */
    public static function map(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'title')->all());
    }

    public static function get(string $title, ?string $default = null): ?string
    {
        return static::map()[$title] ?? $default;
    }

    public static function set(string $title, ?string $value): void
    {
        static::updateOrCreate(['title' => $title], ['value' => (string) $value]);
    }

    public static function voteCountVisible(): bool
    {
        return static::get(self::VOTE_COUNT_VISIBLE, 'false') === 'true';
    }

    public static function votingStartsAt(): ?Carbon
    {
        return static::parseDate(static::get(self::VOTING_STARTS_AT));
    }

    public static function votingEndsAt(): ?Carbon
    {
        return static::parseDate(static::get(self::VOTING_ENDS_AT));
    }

    /**
     * Voting is open only when both bounds are configured and "now" sits between them.
     */
    public static function votingIsOpen(): bool
    {
        $start = static::votingStartsAt();
        $end = static::votingEndsAt();

        if (! $start || ! $end) {
            return false;
        }

        return static::now()->between($start, $end);
    }

    public static function votingHasEnded(): bool
    {
        $end = static::votingEndsAt();

        return $end !== null && static::now()->greaterThan($end);
    }

    public static function now(): Carbon
    {
        return Carbon::now(self::TIMEZONE);
    }

    private static function parseDate(?string $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value, self::TIMEZONE);
        } catch (\Throwable) {
            return null;
        }
    }
}
