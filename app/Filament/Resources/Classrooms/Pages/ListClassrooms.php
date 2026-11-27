<?php

namespace App\Filament\Resources\Classrooms\Pages;

use App\Filament\Resources\Classrooms\ClassroomResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Kelas')
                // Members and the coordinator are managed once the class exists.
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Kelas')
                        ->placeholder('Contoh: SI-3A')
                        ->required()
                        ->maxLength(255)
                        ->unique('classes', 'name'),
                ]),
        ];
    }
}
