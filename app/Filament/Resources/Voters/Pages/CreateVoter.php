<?php

namespace App\Filament\Resources\Voters\Pages;

use App\Filament\Resources\Voters\VoterResource;
use App\Models\Period;
use Filament\Resources\Pages\CreateRecord;

class CreateVoter extends CreateRecord
{
    protected static string $resource = VoterResource::class;

    /** A voter added by hand joins the period being run now. */
    protected function afterCreate(): void
    {
        $periodId = Period::activeId();

        if ($periodId) {
            $this->record->periods()->syncWithoutDetaching([$periodId]);
        }
    }
}
