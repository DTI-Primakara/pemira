<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 1;

    public const ROLE_ADMIN = 2;

    public const ROLE_PARTICIPANT = 3;

    public const ROLE_KORTI = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'roles',
        'class_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The local row behind an SSO account, created the first time its owner
     * signs in.
     *
     * A row is created even for someone who may not vote: they have to reach
     * the dashboard to be told they are not on the roll. Creating one enrols
     * nobody — the voter roll for a period is the admin's doing.
     *
     * @param  array{name: string, email: string, nim: ?string}  $profile
     */
    public static function syncFromSso(array $profile): self
    {
        $email = $profile['email'];
        $nim = $profile['nim'];

        // E-mail first, then student number: a voter the admin imported under a
        // stale e-mail still reaches their own ballot instead of landing on a
        // second, empty account.
        $user = static::query()->where('email', $email)->first()
            ?: ($nim !== null ? static::query()->where('nim', $nim)->first() : null);

        if (! $user) {
            return static::create([
                'name' => $profile['name'] !== '' ? $profile['name'] : $email,
                'email' => $email,
                'nim' => $nim,
                'roles' => self::ROLE_PARTICIPANT,
            ]);
        }

        $changes = [];

        if ($profile['name'] !== '' && $user->name !== $profile['name']) {
            $changes['name'] = $profile['name'];
        }

        // The SSO owns identity, but never at the cost of trampling another
        // row: both columns are unique, so a value already taken is left as it
        // is rather than failing the login outright.
        if ($user->email !== $email && ! static::query()->where('email', $email)->whereKeyNot($user->getKey())->exists()) {
            $changes['email'] = $email;
        }

        if ($nim !== null && $user->nim !== $nim && ! static::query()->where('nim', $nim)->whereKeyNot($user->getKey())->exists()) {
            $changes['nim'] = $nim;
        }

        // Roles and class are never synced: an admin or korti appointment made
        // here must survive its owner signing in again.
        if ($changes !== []) {
            $user->forceFill($changes)->save();
        }

        return $user;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles', 'id_roles');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /** The class this user coordinates, if they are a korti. */
    public function coordinatedClass(): HasOne
    {
        return $this->hasOne(Classroom::class, 'korti_user_id');
    }

    public function isKorti(): bool
    {
        return (int) $this->roles === self::ROLE_KORTI && $this->coordinatedClass()->exists();
    }

    public function userVote(): HasOne
    {
        return $this->hasOne(UserVote::class);
    }

    /** The periods this voter takes part in. */
    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, 'period_user')->withTimestamps();
    }

    /** Whether this voter is on the roll for the period being run now. */
    public function belongsToActivePeriod(): bool
    {
        $periodId = Period::activeId();

        return $periodId !== null && $this->periods()->whereKey($periodId)->exists();
    }

    /**
     * The voter's ballot-status row for a period, created on first access.
     * A voter holds one ballot per period, so taking part again next year does
     * not disturb what they did this year.
     */
    public function ballot(?Period $period = null): UserVote
    {
        $period ??= Period::active();

        if (! $period) {
            // Nothing is being run: hand back an unsaved row so callers can
            // still read a status without creating an orphan ballot.
            return new UserVote(['user_id' => $this->id, 'status' => UserVote::STATUS_NOT_VOTED]);
        }

        return $this->userVote()->firstOrCreate(
            ['user_id' => $this->id, 'period_id' => $period->id],
            ['status' => UserVote::STATUS_NOT_VOTED]
        );
    }

    /**
     * Study-programme code encoded in the student number, e.g. 23 0102 001 -> "0102".
     */
    public function prodiCode(): ?string
    {
        $nim = (string) $this->nim;

        return strlen($nim) >= 6 ? substr($nim, 2, 4) : null;
    }

    /**
     * Elections this voter may take part in: university-wide ones plus the one
     * belonging to their study programme, within the period being run now and
     * only if the voter is on that period's roll. Elections without candidates
     * are skipped.
     *
     * @return Collection<int, Event>
     */
    public function eligibleEvents(): Collection
    {
        $periodId = Period::activeId();

        if ($periodId === null || ! $this->belongsToActivePeriod()) {
            return new Collection;
        }

        $prodiCode = $this->prodiCode();

        return Event::query()
            ->where('period_id', $periodId)
            ->where(function ($query) use ($prodiCode) {
                $query->whereNull('id_prodi');

                if ($prodiCode !== null) {
                    $query->orWhere('id_prodi', $prodiCode);
                }
            })
            ->has('candidates')
            ->with(['candidates' => fn ($query) => $query->orderBy('number')])
            ->orderBy('id')
            ->get();
    }

    public function isAdmin(): bool
    {
        return in_array((int) $this->roles, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
