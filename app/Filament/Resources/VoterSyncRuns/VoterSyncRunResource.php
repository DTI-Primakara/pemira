<?php

namespace App\Filament\Resources\VoterSyncRuns;

use App\Filament\Resources\VoterSyncRuns\Pages\ListVoterSyncRuns;
use App\Models\VoterSyncRun;
use BackedEnum;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only history of "Tarik Data SISKA" runs.
 *
 * Deliberately has no create, edit or delete: a run is a record of what
 * happened to the voter roll, and the roll decides who may cast a ballot.
 */
class VoterSyncRunResource extends Resource
{
    protected static ?string $model = VoterSyncRun::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowDownTray;

    protected static ?string $modelLabel = 'Tarikan Data';

    protected static ?string $pluralModelLabel = 'Sinkronisasi SISKA';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        VoterSyncRun::STATUS_COMPLETED => 'success',
                        VoterSyncRun::STATUS_FAILED => 'danger',
                        default => 'warning',
                    }),

                TextEntry::make('period.name')->label('Periode'),

                TextEntry::make('startedBy.name')
                    ->label('Dijalankan oleh')
                    ->placeholder('—'),

                TextEntry::make('total_students')->label('Mahasiswa aktif di SISKA')->placeholder('—'),
                TextEntry::make('seen_count')->label('Terbaca'),
                TextEntry::make('created_count')->label('Akun baru'),
                TextEntry::make('updated_count')->label('Akun diperbarui'),
                TextEntry::make('enrolled_count')->label('Baru masuk daftar pemilih'),
                TextEntry::make('removed_count')->label('Dikeluarkan dari daftar'),
                TextEntry::make('skipped_count')->label('Dilewati'),

                TextEntry::make('started_at')->label('Mulai')->dateTime('d M Y H:i'),
                TextEntry::make('finished_at')->label('Selesai')->dateTime('d M Y H:i')->placeholder('—'),

                TextEntry::make('error')
                    ->label('Penyebab gagal')
                    ->columnSpanFull()
                    ->visible(fn (VoterSyncRun $record) => filled($record->error)),

                KeyValueEntry::make('issues')
                    ->label('Catatan per mahasiswa')
                    ->keyLabel('NIM')
                    ->valueLabel('Keterangan')
                    ->state(fn (VoterSyncRun $record) => collect($record->issues ?? [])
                        ->mapWithKeys(fn (array $issue, int $index) => [
                            // The NIM alone would collapse repeat entries for
                            // one student into a single line.
                            $issue['nim'].'  #'.($index + 1) => $issue['reason'],
                        ])
                        ->all())
                    ->columnSpanFull()
                    ->visible(fn (VoterSyncRun $record) => filled($record->issues)),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('started_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        VoterSyncRun::STATUS_COMPLETED => 'success',
                        VoterSyncRun::STATUS_FAILED => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        VoterSyncRun::STATUS_COMPLETED => 'Selesai',
                        VoterSyncRun::STATUS_FAILED => 'Gagal',
                        default => 'Berjalan',
                    }),

                TextColumn::make('progress')
                    ->label('Halaman')
                    ->state(fn (VoterSyncRun $record) => $record->current_page.' / '.($record->last_page ?? '?')),

                TextColumn::make('seen_count')->label('Terbaca')->badge(),
                TextColumn::make('enrolled_count')->label('Masuk')->badge()->color('success'),
                TextColumn::make('removed_count')->label('Keluar')->badge()->color('danger'),
                TextColumn::make('skipped_count')->label('Dilewati')->badge()->color('warning'),

                TextColumn::make('startedBy.name')
                    ->label('Oleh')
                    ->placeholder('—'),
            ])
            ->defaultSort('id', 'desc')
            // The page is how an admin watches a queued run progress, so it has
            // to refresh itself while one is in flight.
            ->poll(fn () => VoterSyncRun::isRunning() ? '5s' : null);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVoterSyncRuns::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
