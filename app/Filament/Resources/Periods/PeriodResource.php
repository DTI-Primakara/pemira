<?php

namespace App\Filament\Resources\Periods;

use App\Filament\Resources\Periods\Pages\ListPeriods;
use App\Models\Period;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeriodResource extends Resource
{
    protected static ?string $model = Period::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowPath;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Periode';

    protected static ?string $pluralModelLabel = 'Periode Pemilihan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nama Periode')
                    ->placeholder('PEMIRA 2026')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                DateTimePicker::make('starts_at')
                    ->label('Voting Dibuka')
                    ->helperText('Waktu Indonesia Tengah (WITA).')
                    ->seconds(false)
                    ->native(false),

                DateTimePicker::make('ends_at')
                    ->label('Voting Ditutup')
                    ->helperText('Harus setelah waktu dibuka.')
                    ->seconds(false)
                    ->native(false)
                    ->after('starts_at'),

                Toggle::make('is_vote_count_visible')
                    ->label('Tampilkan Hasil Sementara')
                    ->helperText('Saat aktif, rekap suara periode ini tampil di halaman depan.')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Periode')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Period $record) => $record->is_active ? 'Sedang berjalan' : null),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('starts_at')
                    ->label('Dibuka')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—'),

                TextColumn::make('ends_at')
                    ->label('Ditutup')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—'),

                IconColumn::make('is_vote_count_visible')
                    ->label('Hasil Tampil')
                    ->boolean(),

                TextColumn::make('events_count')
                    ->label('Pemilihan')
                    ->counts('events')
                    ->badge(),

                TextColumn::make('voters_count')
                    ->label('Peserta')
                    ->counts('voters')
                    ->badge(),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('Aktifkan')
                    ->icon(Heroicon::CheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan periode ini?')
                    ->modalDescription('Periode yang sedang aktif akan dinonaktifkan. Data periode lama tetap tersimpan dan tidak ikut dihitung.')
                    ->visible(fn (Period $record) => ! $record->is_active)
                    ->action(function (Period $record) {
                        $record->activate();

                        Notification::make()
                            ->title('Periode '.$record->name.' sekarang aktif.')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),

                // History is the point of this table, so a period that already
                // holds elections cannot be deleted by accident.
                DeleteAction::make()
                    ->visible(fn (Period $record) => ! $record->is_active && $record->events()->doesntExist()),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeriods::route('/'),
        ];
    }
}
