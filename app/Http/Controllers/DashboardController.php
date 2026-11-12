<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Event;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function showHomepage()
    {
        $visible = Config::voteCountVisible();

        return Inertia::render('Homepage', [
            // While the tally is hidden the counts are not computed or sent at all:
            // shipping them and merely not rendering would leave them readable in
            // the page source.
            'groups' => $visible ? $this->tallyGroups() : [],
            'voteCountVisible' => $visible,
        ]);
    }

    /**
     * Public tally, grouped the way the homepage renders it: university-wide
     * elections first, then one block per faculty.
     *
     * Only aggregate counts leave the server. Shipping the individual vote rows
     * would expose their timestamps, which is enough to reconstruct who voted
     * for whom across elections.
     */
    private function tallyGroups(): array
    {
        $events = Event::query()
            ->with(['prodi', 'candidates' => fn ($query) => $query->withCount('votes')->orderBy('number')])
            ->has('candidates')
            ->orderBy('id')
            ->get();

        $groups = [];

        $universityWide = $events->whereNull('id_prodi');

        if ($universityWide->isNotEmpty()) {
            $groups[] = [
                'key' => 'universitas',
                'title' => null,
                'subtitle' => null,
                'events' => $this->presentEvents($universityWide),
            ];
        }

        $byFaculty = $events
            ->filter(fn (Event $event) => $event->id_prodi !== null)
            ->groupBy(fn (Event $event) => $event->prodi?->faculty ?? 'Program Studi Lainnya');

        foreach ($byFaculty as $faculty => $facultyEvents) {
            $groups[] = [
                'key' => str($faculty)->slug()->value(),
                'title' => $faculty,
                'subtitle' => 'Tentukan pilihanmu untuk '.($facultyEvents->first()->prodi?->faculty_short ?? $faculty).' lebih baik!',
                'events' => $this->presentEvents($facultyEvents),
            ];
        }

        return $groups;
    }

    private function presentEvents(Collection $events): array
    {
        return $events->map(fn (Event $event) => [
            'id' => $event->id,
            'title' => $event->title,
            'type' => $event->type,
            'candidates' => $event->candidates->map(fn ($candidate) => [
                'id' => $candidate->id,
                'name_candidates' => $candidate->name_candidates,
                'number' => $candidate->number,
                'image' => $candidate->image,
                'votes_count' => $candidate->votes_count,
            ])->values(),
        ])->values()->all();
    }
}
