<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use App\Models\Event;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VoteController extends Controller
{
    public function getCandidates()
    {
        $nim = Auth::user()->nim;

        $prodiCode = substr($nim, 2, 4);

        $events = Event::whereNull('id_prodi')->orWhere('id_prodi', $prodiCode)->orderBy('id')->with('candidates')->get();

        return Inertia::render('Voting', [
            'events' => $events
        ]);
    }

    public function submitVote(Request $request)
    {
        $request->validate([
            'votes' => 'required|array|min:1',
            'votes.*.event_id' => 'required|exists:events,id',
            'votes.*.candidate_id' => 'required|exists:candidates,id',
        ]);

        $status = Auth::user()->userVote->status;

        if ($status != 'not voted') {
            return Inertia::render('InvalidNIM');
        }

        try {
            foreach ($request->votes as $vote) {
                Vote::create([
                    'event_id' => $vote['event_id'],
                    'candidate_id' => $vote['candidate_id'],
                    'voted_at' => now(),
                ]);
            }
            User::find(Auth::id())->userVote->update(
                ['status' => 'voted']
            );
            return redirect()->route('dashboard')->with('success', 'Vote submitted successfully.');
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => $th]);
        }
    }
}
