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

    /** Campus timezone; the voting window is kept as wall-clock time here. */
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

    public static function now(): Carbon
    {
        return Carbon::now(self::TIMEZONE);
    }
}
