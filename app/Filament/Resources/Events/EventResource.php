<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Models\Event;
use App\Models\Period;
use App\Models\Prodi;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Pemilihan';

    protected static ?string $pluralModelLabel = 'Pemilihan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama Pemilihan')
                            ->placeholder('Contoh: Majelis Permusyawaratan Rakyat')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('type')
                            ->label('Jenis / Singkatan')
                            ->placeholder('Contoh: BEM, DPM, HIMA, MPR')
                            ->helperText('Ditampilkan sebagai label jenis pemilihan di halaman voting.')
                            ->required()
                            ->maxLength(255)
                            ->datalist(fn () => Event::query()->distinct()->pluck('type')->filter()->all()),

                        Select::make('id_prodi')
                            ->label('Program Studi')
                            ->placeholder('Seluruh universitas (BEM, DPM, MPR, ...)')
                            ->options(fn () => Prodi::query()->orderBy('id_prodi')->pluck('name', 'id_prodi'))
                            ->searchable()
                            ->helperText('Kosongkan bila pemilihan ini terbuka untuk seluruh mahasiswa. Bila diisi, hanya mahasiswa prodi tersebut yang dapat memilih.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Pemilihan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('prodi.name')
                    ->label('Program Studi')
                    ->placeholder('Seluruh universitas')
                    ->sortable(),

                TextColumn::make('candidates_count')
                    ->label('Jumlah Paslon')
                    ->counts('candidates')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('id_prodi')
                    ->label('Program Studi')
                    ->options(fn () => Prodi::query()->orderBy('id_prodi')->pluck('name', 'id_prodi')),
            ])
            ->defaultSort('id');
    }

    public static function getRelations(): array
    {
        return [];
    }

    /** Admins only ever see and edit the period being run now. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('period_id', Period::activeId());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
