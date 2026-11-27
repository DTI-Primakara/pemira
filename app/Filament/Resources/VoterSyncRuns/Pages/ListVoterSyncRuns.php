<?php

namespace App\Filament\Resources\VoterSyncRuns\Pages;

use App\Filament\Resources\VoterSyncRuns\VoterSyncRunResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ManageRecords;

class ListVoterSyncRuns extends ManageRecords
{
    protected static string $resource = VoterSyncRunResource::class;

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return parent::table($table)->recordActions([
            ViewAction::make()->label('Rincian'),
        ]);
    }
}
