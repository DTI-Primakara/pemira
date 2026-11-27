<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\User;
use App\Models\UserVote;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoordinatorController extends Controller
{
    public function index()
    {
        /** @var Classroom $classroom */
        $classroom = Auth::user()->coordinatedClass()->firstOrFail();

        $periodId = Period::activeId();

        // Only classmates on this period's roll, and only this period's ballot
        // status: last year's "voted" must not show up as done this year.
        $members = $classroom->members()
            ->when($periodId, fn ($query) => $query->whereHas('periods', fn ($roll) => $roll->whereKey($periodId)))
            ->with(['userVote' => fn ($query) => $query->where('period_id', $periodId)])
            ->orderBy('name')
            ->get()
            ->map(fn (User $member) => [
                'id' => $member->id,
                'name' => $member->name,
                'nim' => $member->nim,
                // Only whether a ballot was cast is ever exposed, never its contents.
                'has_voted' => ($member->userVote?->status ?? UserVote::STATUS_NOT_VOTED) === UserVote::STATUS_VOTED,
            ])
            ->values();

        return Inertia::render('Koordinator', [
            'classroom' => [
                'id' => $classroom->id,
                'name' => $classroom->name,
            ],
            'members' => $members,
            'summary' => [
                'total' => $members->count(),
                'voted' => $members->where('has_voted', true)->count(),
                'notVoted' => $members->where('has_voted', false)->count(),
            ],
        ]);
    }
}
