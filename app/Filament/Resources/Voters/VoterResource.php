<?php

namespace App\Filament\Resources\Voters;

use App\Filament\Resources\Voters\Pages\CreateVoter;
use App\Filament\Resources\Voters\Pages\EditVoter;
use App\Filament\Resources\Voters\Pages\ListVoters;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VoterResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'voter';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->numeric()
                            ->minLength(10)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->maxLength(255)
                            ->email(),
                        Hidden::make('roles')
                            ->default(3)
                    ])
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                ->modifyQueryUsing(function ($query) {
                    return $query
                        ->leftJoin('user_vote', 'user_vote.user_id', '=', 'users.id')
                        ->where('users.roles', 3)
                        ->select([
                            'users.*',
                            'user_vote.status as vote_status',
                        ]);
                })
                ->columns([
                    TextColumn::make('name')
                        ->label("Nama")
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('nim')
                        ->label('NIM')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('email')
                        ->label('Email')
                        ->sortable()
                        ->searchable(),
                    TextColumn::make('vote_status')
                        ->label('Status')
                        ->sortable()
                        ->searchable(
                            query: function ($query, string $search) {
                                $query->orWhere('user_vote.status', 'like', "%{$search}%");
                            }
                        )
                        ->default('Not Login')
                        ->formatStateUsing(fn ($state) => Str::title($state))
                ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVoters::route('/'),
            'create' => CreateVoter::route('/create'),
            'edit' => EditVoter::route('/{record}/edit'),
        ];
    }
}
