<?php

namespace App\Filament\Resources\Paslons;

use App\Filament\Resources\Paslons\Pages\CreatePaslon;
use App\Filament\Resources\Paslons\Pages\EditPaslon;
use App\Filament\Resources\Paslons\Pages\ListPaslons;
use App\Filament\Resources\Paslons\Schemas\PaslonForm;
use App\Filament\Resources\Paslons\Tables\PaslonsTable;
use App\Models\Candidate;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PaslonResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'paslon';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name_candidates')
                            ->label('Nama Paslon')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('number')
                            ->label('Nomor Urut')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Select::make('event_id')
                            ->label('Pemilihan')
                            ->relationship('event', 'title')
                            ->required()
                            ->live(),
                        Textarea::make('vision')
                            ->label('Visi')
                            ->rows(4)
                            ->required(),
                        Textarea::make('mission')
                            ->label('Misi')
                            ->rows(4)
                            ->required(),
                        FileUpload::make('image')
                            ->label('Foto Paslon')
                            ->image()
                            ->disk('public')
                            ->directory('paslon-images')
                            ->visibility('public')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->join('events', 'events.id', '=', 'candidates.event_id')
                    ->select('candidates.*', DB::raw('(select count(*) from `votes` where `candidates`.`id` = `votes`.`candidate_id`) as `votes_count`'))
            )
            ->columns([
                TextColumn::make('name_candidates')
                    ->label('Nama Paslon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('number')
                    ->label('Nomor Urut')
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label('Pemilihan')
                    ->sortable()
                    ->searchable(),
            ])


        ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $event = \App\Models\Event::find($data['event_id']);

        if ($event) {
            if (strtolower($event->title) === 'bem') {
                $data['position'] = 'Calon Ketua dan Wakil Ketua BEM';
            } else {
                $data['position'] = 'Calon Ketua ' . $event->title;
            }
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $event = \App\Models\Event::find($data['event_id']);

        if ($event) {
            if (strtolower($event->title) === 'bem') {
                $data['position'] = 'Calon Ketua dan Wakil Ketua BEM';
            } else {
                $data['position'] = 'Calon Ketua ' . $event->title;
            }
        }

        return $data;
    }



    public static function getPages(): array
    {
        return [
            'index' => ListPaslons::route('/'),
            'create' => CreatePaslon::route('/create'),
            'edit' => EditPaslon::route('/{record}/edit'),
        ];
    }

    protected static ?string $pluralModelLabel = 'Paslon';
}
