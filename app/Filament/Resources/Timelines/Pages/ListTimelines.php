<?php

namespace App\Filament\Resources\Timelines\Pages;

use App\Filament\Resources\Timelines\TimelineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ListTimelines extends ManageRecords
{
    protected static string $resource = TimelineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
