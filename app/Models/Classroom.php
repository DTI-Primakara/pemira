<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Classroom extends Model
{
    use HasFactory;

    /** "class" is a reserved word in PHP, so the model is named Classroom. */
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'korti_user_id',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'class_id');
    }

    public function korti(): BelongsTo
    {
        return $this->belongsTo(User::class, 'korti_user_id');
    }

    /**
     * Add students to this class by student number.
     *
     * Returns a report so the caller can tell the admin exactly what happened
     * instead of silently dropping unknown numbers.
     *
     * @return array{added: int, moved: int, already: int, missing: array<int, string>}
     */
    public function addMembersByNim(string $rawNims): array
    {
        $nims = $this->parseNims($rawNims);

        if ($nims->isEmpty()) {
            return ['added' => 0, 'moved' => 0, 'already' => 0, 'missing' => []];
        }

        $users = User::query()->whereIn('nim', $nims)->get();
        $missing = $nims->diff($users->pluck('nim'))->values()->all();

        $added = 0;
        $moved = 0;
        $already = 0;

        foreach ($users as $user) {
            if ((int) $user->class_id === (int) $this->id) {
                $already++;

                continue;
            }

            if ($user->class_id !== null) {
                $moved++;
            } else {
                $added++;
            }

            $user->forceFill(['class_id' => $this->id])->save();
        }

        return ['added' => $added, 'moved' => $moved, 'already' => $already, 'missing' => $missing];
    }

    /**
     * Appoint a member as coordinator, demoting whoever held the role before.
     */
    public function appointKorti(?int $userId): void
    {
        // Read the previous holder from the column, not the relation: a relation
        // loaded earlier in the same request would still be cached as null.
        $previous = $this->korti_user_id ? User::find($this->korti_user_id) : null;

        if ($previous && $previous->id !== $userId) {
            $previous->forceFill(['roles' => User::ROLE_PARTICIPANT])->save();
        }

        $this->forceFill(['korti_user_id' => $userId])->save();

        if ($userId === null) {
            return;
        }

        $next = User::find($userId);

        if ($next) {
            $next->forceFill([
                'roles' => User::ROLE_KORTI,
                'class_id' => $this->id,
            ])->save();
        }
    }

    /**
     * @return Collection<int, string>
     */
    private function parseNims(string $raw): Collection
    {
        return collect(preg_split('/[\s,;]+/', $raw) ?: [])
            ->map(fn ($nim) => trim((string) $nim))
            ->filter()
            ->unique()
            ->values();
    }
}
