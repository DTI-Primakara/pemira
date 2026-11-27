<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vote\SubmitVoteRequest;
use App\Models\Config;
use App\Models\Period;
use App\Models\UserVote;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VoteController extends Controller
{
    public function getCandidates()
    {
        return Inertia::render('Voting', [
            'events' => Auth::user()->eligibleEvents()->values(),
        ]);
    }

    public function submitVote(SubmitVoteRequest $request)
    {
        $userId = Auth::id();
        $periodId = Period::activeId();

        $accepted = DB::transaction(function () use ($request, $userId, $periodId) {
            // Serialise concurrent submissions from the same voter so a double
            // submit cannot slip past the status check.
            $ballot = UserVote::query()
                ->where('user_id', $userId)
                ->where('period_id', $periodId)
                ->lockForUpdate()
                ->first();

            if (! $ballot || $ballot->status !== 'not voted') {
                return false;
            }

            // Timestamps are coarsened to the hour: precise timestamps would let
            // anyone reading the tally group a single voter's choices together.
            $castAt = Config::now()->startOfHour();

            Vote::insert(
                collect($request->validated('votes'))
                    ->map(fn (array $vote) => [
                        'event_id' => (int) $vote['event_id'],
                        'candidate_id' => (int) $vote['candidate_id'],
                        'voted_at' => $castAt,
                        'created_at' => $castAt,
                        'updated_at' => $castAt,
                    ])
                    ->all()
            );

            $ballot->update(['status' => 'voted']);

            return true;
        });

        if (! $accepted) {
            return back()->withErrors(['votes' => 'Hak suara Anda sudah digunakan.']);
        }

        return redirect()->route('dashboard')->with('success', 'Suara berhasil dikirim.');
    }
}
