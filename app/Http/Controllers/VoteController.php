<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoteController extends Controller
{
    public function getCandidates()
    {
        $nim = session('user')['data']['nim'];

        if (!$nim) {
            return Inertia::render('InvalidNIM');
        }

        $user = EligibleUser::where('nim', $nim)->first();

        if (!$user) {
            return Inertia::render('InvalidNIM');
        }

        if ($user->status != 'not voted') {
            return redirect()->route('dashboard');
        }

        $prodiCode = substr($nim, 2, 4);

        $events = Event::whereNull('id_prodi')->orWhere('id_prodi', $prodiCode)->with('candidates')->get();
        // dd($events);

        return Inertia::render('Voting', [
            'events' => $events
        ]);
    }

    public function submitVote(Request $request)
    {
        $nim = session('user')['data']['nim'];

        if (!$nim) {
            return Inertia::render('InvalidNIM');
        }

        $request->validate([
            'votes' => 'required|array|min:1',
            'votes.*.event_id' => 'required|exists:events,id',
            'votes.*.candidate_id' => 'required|exists:candidates,id',
        ]);

        $user = EligibleUser::where('nim', $nim)->first();

        if (!$user) {
            return Inertia::render('InvalidNIM');
        }

        if ($user->status != 'not voted') {
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
            $user->update(['status' => 'voted']);
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => $th]);
        }
    }
}
