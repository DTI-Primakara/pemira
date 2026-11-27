<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * One run of PEMIRA. Elections, agenda, voters and ballots all hang off a
 * period, so last year's results stay in the database without ever being
 * counted, edited or voted on again.
 *
 * Exactly one period is active at a time; everything public reads from it.
 */
class Period extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'pemira.active_period';

    protected $fillable = [
        'name',
        'starts_at',
        'ends_at',
        'is_vote_count_visible',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_vote_count_visible' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** The period every public page and every ballot refers to. */
    public static function active(): ?self
    {
        $id = Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->where('is_active', true)->value('id'));

        return $id ? static::find($id) : null;
    }

    public static function activeId(): ?int
    {
        return static::active()?->id;
    }

    /** Make this the one active period, in a single transaction. */
    public function activate(): void
    {
        DB::transaction(function () {
            static::query()->where('id', '!=', $this->id)->update(['is_active' => false]);

            $this->forceFill(['is_active' => true])->save();
        });

        Cache::forget(self::CACHE_KEY);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(UserVote::class);
    }

    /** The students entitled to vote in this period. */
    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'period_user')->withTimestamps();
    }

    /**
     * The window is stored as wall-clock time in the campus timezone, which is
     * also how the admin types it, so it is read back the same way rather than
     * as UTC.
     */
    public function votingStartsAt(): ?Carbon
    {
        return $this->wallClock($this->starts_at);
    }

    public function votingEndsAt(): ?Carbon
    {
        return $this->wallClock($this->ends_at);
    }

    public function votingIsOpen(): bool
    {
        $start = $this->votingStartsAt();
        $end = $this->votingEndsAt();

        if (! $start || ! $end) {
            return false;
        }

        return Config::now()->between($start, $end);
    }

    public function votingHasEnded(): bool
    {
        $end = $this->votingEndsAt();

        return $end !== null && Config::now()->greaterThan($end);
    }

    private function wallClock(?Carbon $value): ?Carbon
    {
        return $value ? Carbon::parse($value->format('Y-m-d H:i:s'), Config::TIMEZONE) : null;
    }
}
