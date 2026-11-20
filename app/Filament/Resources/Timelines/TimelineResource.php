<?php

namespace App\Filament\Resources\Timelines;

use App\Filament\Resources\Timelines\Pages\ListTimelines;
use App\Models\Timeline;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimelineResource extends Resource
{
    protected static ?string $model = Timeline::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Agenda';

    protected static ?string $pluralModelLabel = 'Linimasa';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label('Nama Agenda')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                DatePicker::make('starts_on')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->native(false)
                    ->displayFormat('d F Y'),

                DatePicker::make('ends_on')
                    ->label('Tanggal Selesai')
                    ->helperText('Kosongkan kalau agenda hanya berlangsung satu hari.')
                    ->native(false)
                    ->displayFormat('d F Y')
                    ->after('starts_on'),

                TextInput::make('position')
                    ->label('Urutan')
                    ->helperText('Angka kecil tampil lebih dulu di halaman depan.')
                    ->numeric()
                    ->minValue(0)
                    ->default(fn () => (Timeline::max('position') ?? 0) + 1)
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position')->label('Urutan')->sortable(),
                TextColumn::make('title')->label('Agenda')->searchable(),
                TextColumn::make('starts_on')->label('Mulai')->date('d F Y')->sortable(),
                TextColumn::make('ends_on')->label('Selesai')->date('d F Y')->placeholder('—'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('position');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimelines::route('/'),
        ];
    }
}
