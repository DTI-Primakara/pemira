<?php

namespace App\Http\Requests\Vote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class SubmitVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'votes' => ['required', 'array', 'min:1', 'max:100'],
            'votes.*.event_id' => ['required', 'integer'],
            'votes.*.candidate_id' => ['required', 'integer'],
        ];
    }

    /**
     * The shape of the ballot is decided by the server, never by the client.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $eligible = $this->user()->eligibleEvents()->keyBy('id');
                $votes = collect($this->input('votes'));
                $eventIds = $votes->pluck('event_id')->map(fn ($id) => (int) $id);

                // Ballot stuffing: the same election may not appear twice.
                if ($eventIds->duplicates()->isNotEmpty()) {
                    $validator->errors()->add('votes', 'Terdeteksi lebih dari satu suara untuk pemilihan yang sama.');

                    return;
                }

                // Cross-prodi voting: every election must be one this voter may take part in.
                if ($eventIds->diff($eligible->keys())->isNotEmpty()) {
                    $validator->errors()->add('votes', 'Terdapat pemilihan yang bukan hak suara Anda.');

                    return;
                }

                // Partial ballots would burn the voter's remaining rights.
                if ($eligible->keys()->diff($eventIds)->isNotEmpty()) {
                    $validator->errors()->add('votes', 'Seluruh pemilihan wajib diisi sebelum suara dikirim.');

                    return;
                }

                // The candidate must actually stand in the election they are voted for.
                foreach ($votes as $index => $vote) {
                    $event = $eligible->get((int) $vote['event_id']);

                    if (! $event->candidates->contains('id', (int) $vote['candidate_id'])) {
                        $validator->errors()->add(
                            "votes.{$index}.candidate_id",
                            'Kandidat tidak terdaftar pada pemilihan tersebut.'
                        );
                    }
                }
            },
        ];
    }
}
