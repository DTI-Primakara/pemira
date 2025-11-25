<?php

namespace App\Filament\Resources\Voters\Pages;

use App\Filament\Resources\Voters\VoterResource;
use App\Imports\VoterImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use EightyNine\ExcelImport\ExcelImportAction;

class ListVoters extends ListRecords
{
    protected static string $resource = VoterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->slideOver()
                ->color('success')
                ->use(VoterImport::class)
                ->validateUsing([
                    'name' => 'required|string|max:255',
                    'nim' => 'required|numeric|unique:users,nim', 
                    'email' => 'required|email|max:255|unique:users,email',
                ]),

            Actions\CreateAction::make(),
        ];
    }
}