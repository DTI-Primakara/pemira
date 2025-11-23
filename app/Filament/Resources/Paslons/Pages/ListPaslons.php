<?php

namespace App\Filament\Resources\Paslons\Pages;

use App\Filament\Resources\Paslons\PaslonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaslons extends ListRecords
{
    protected static string $resource = PaslonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
