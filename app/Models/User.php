<?php

namespace App\Models;

use App\Models\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles', 'id_roles');
    }

    public function userVote(): HasOne
    {
        return $this->hasOne(UserVote::class);
    }

    /**
     * The voter's ballot-status row, created on first access.
     */
    public function ballot(): UserVote
    {
        return $this->userVote()->firstOrCreate(
            ['user_id' => $this->id],
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
     * belonging to their study programme. Elections without candidates are skipped.
     *
     * @return Collection<int, Event>
     */
    public function eligibleEvents(): Collection
    {
        $prodiCode = $this->prodiCode();

        return Event::query()
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
