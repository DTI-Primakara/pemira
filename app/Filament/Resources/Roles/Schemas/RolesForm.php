<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RolesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_roles')
                ->required()
                ->maxLength(255)
                ->label('Nama Roles')->unique(ignoreRecord: true)
            ]);
    }
}
