<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\Period;
use App\Models\Timeline;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function showHomepage()
    {
        $period = Period::active();
        $tallyVisible = (bool) $period?->is_vote_count_visible;

        $events = $period ? $this->electionsWithCandidates($tallyVisible) : new Collection;

        return Inertia::render('Homepage', [
            'elections' => $this->presentElections($events),
            // While the tally is hidden the counts are not sent at all: shipping
            // them and merely not rendering would leave them readable in the
            // page source.
            'groups' => $tallyVisible ? $this->tallyGroups($events) : [],
            'voteCountVisible' => $tallyVisible,
            'timelines' => $this->agenda(),
        ]);
    }

    /**
     * The public agenda, managed from the admin panel. Dates are sent as plain
     * days because the homepage only ever renders a date.
     *
     * @return array<int, array<string, mixed>>
     */
    private function agenda(): array
    {
        return Timeline::query()
            ->forActivePeriod()
            ->ordered()
            ->get()
            ->map(fn (Timeline $entry) => [
                'id' => $entry->id,
                'title' => $entry->title,
                'starts_on' => $entry->starts_on->toDateString(),
                'ends_on' => $entry->ends_on?->toDateString(),
            ])
            ->all();
    }

    /**
     * Every election that actually has candidates, ordered as created.
     *
     * @return Collection<int, Event>
     */
    private function electionsWithCandidates(bool $withVoteCounts): Collection
    {
        return Event::query()
            ->forActivePeriod()
            ->with([
                'prodi',
                'candidates' => function ($query) use ($withVoteCounts) {
                    $query->orderBy('number');

                    if ($withVoteCounts) {
                        $query->withCount('votes');
                    }
                },
            ])
            ->has('candidates')
            ->orderBy('id')
            ->get();
    }

    /**
     * The public candidate showcase: who stands in which election, and who is
     * entitled to vote in it. Driven entirely by the admin panel.
     *
     * @param  Collection<int, Event>  $events
     */
    private function presentElections(Collection $events): array
    {
        return $events->map(function (Event $event) {
            // A blank box is a real ballot option and keeps counting in the
            // tally, but there is nothing to show off in the showcase.
            $candidates = $event->candidates->reject(fn (Candidate $candidate) => $candidate->is_blank_box);

            if ($candidates->isEmpty()) {
                return null;
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'shortLabel' => $this->shortLabelFor($event),
                'type' => $event->type,
                'eligibility' => $event->isUniversityWide()
                    ? 'Terbuka untuk seluruh mahasiswa'
                    : 'Khusus mahasiswa '.($event->prodi?->name ?? 'program studi terkait'),
                'candidates' => $candidates->map(fn (Candidate $candidate) => [
                    'id' => $candidate->id,
                    'name_candidates' => $candidate->name_candidates,
                    'number' => $candidate->number,
                    'position' => $candidate->position,
                    'image_url' => $candidate->showcaseImageUrl(),
                ])->values(),
            ];
        })->filter()->values()->all();
    }

    /**
     * Short badge above the election title, e.g. "DPM", "MPR", "HIMA SI".
     */
    private function shortLabelFor(Event $event): string
    {
        if ($event->isUniversityWide()) {
            return $event->type;
        }

        return trim($event->type.' '.($event->prodi?->short_name ?? ''));
    }

    /**
     * Public tally, grouped the way the homepage renders it: university-wide
     * elections first, then one block per faculty.
     *
     * Only aggregate counts leave the server. Shipping the individual vote rows
     * would expose their timestamps, which is enough to reconstruct who voted
     * for whom across elections.
     *
     * @param  Collection<int, Event>  $events
     */
    private function tallyGroups(Collection $events): array
    {
        $groups = [];

        $universityWide = $events->filter(fn (Event $event) => $event->isUniversityWide());

        if ($universityWide->isNotEmpty()) {
            $groups[] = [
                'key' => 'universitas',
                'title' => null,
                'subtitle' => null,
                'events' => $this->presentTallyEvents($universityWide),
            ];
        }

        $byFaculty = $events
            ->reject(fn (Event $event) => $event->isUniversityWide())
            ->groupBy(fn (Event $event) => $event->prodi?->faculty ?? 'Program Studi Lainnya');

        foreach ($byFaculty as $faculty => $facultyEvents) {
            $groups[] = [
                'key' => str($faculty)->slug()->value(),
                'title' => $faculty,
                'subtitle' => 'Tentukan pilihanmu untuk '.($facultyEvents->first()->prodi?->faculty_short ?? $faculty).' lebih baik!',
                'events' => $this->presentTallyEvents($facultyEvents),
            ];
        }

        return $groups;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Event>  $events
     */
    private function presentTallyEvents($events): array
    {
        return $events->map(fn (Event $event) => [
            'id' => $event->id,
            'title' => $event->title,
            'type' => $event->type,
            'candidates' => $event->candidates->map(fn (Candidate $candidate) => [
                'id' => $candidate->id,
                'name_candidates' => $candidate->name_candidates,
                'number' => $candidate->number,
                'votes_count' => $candidate->votes_count,
            ])->values(),
        ])->values()->all();
    }
}
