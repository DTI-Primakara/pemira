<?php

namespace App\Filament\Resources\Voters\Pages;

use App\Filament\Resources\Voters\VoterResource;
use App\Imports\VoterImport;
use App\Jobs\ImportSiskaStudentPage;
use App\Models\Period;
use App\Models\VoterSyncRun;
use App\Services\SiskaService;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListVoters extends ListRecords
{
    protected static string $resource = VoterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->siskaSyncAction(),

            ExcelImportAction::make()
                ->slideOver()
                ->color('success')
                ->use(VoterImport::class)
                ->validateUsing([
                    'name' => 'required|string|max:255',
                    'nim' => 'required|numeric',
                    'email' => 'required|email|max:255',
                ]),

            Actions\Action::make('copyVoters')
                ->label('Salin Peserta')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->visible(fn () => Period::activeId() !== null && Period::query()->count() > 1)
                ->schema([
                    Select::make('source_period_id')
                        ->label('Salin dari periode')
                        ->helperText('Peserta periode tersebut ditambahkan ke periode yang sedang aktif. Peserta yang sudah ada tidak digandakan.')
                        ->options(fn () => Period::query()
                            ->where('id', '!=', Period::activeId())
                            ->orderByDesc('id')
                            ->pluck('name', 'id'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $target = Period::active();
                    $source = Period::find($data['source_period_id']);

                    if (! $target || ! $source) {
                        return;
                    }

                    $before = $target->voters()->count();
                    $target->voters()->syncWithoutDetaching($source->voters()->pluck('users.id')->all());
                    $added = $target->voters()->count() - $before;

                    Notification::make()
                        ->title($added.' peserta disalin dari '.$source->name.'.')
                        ->body($added === 0 ? 'Semua peserta periode tersebut sudah terdaftar.' : null)
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }

    /**
     * Pull SISKA's active-student list and make the roll match it.
     *
     * Queued, one page at a time, so the button returns immediately and the
     * work survives a closed browser tab. Progress and results are on the
     * Sinkronisasi SISKA page.
     */
    private function siskaSyncAction(): Actions\Action
    {
        return Actions\Action::make('syncFromSiska')
            ->label('Tarik Data SISKA')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('primary')
            ->visible(fn () => Period::activeId() !== null)
            // Shown but dead when the server has no SISKA key, rather than
            // hidden: a button that simply is not there leaves an admin
            // hunting for a feature instead of reading why it is unavailable.
            ->disabled(fn () => ! app(SiskaService::class)->isConfigured())
            ->tooltip(fn () => app(SiskaService::class)->isConfigured()
                ? null
                : 'SISKA_API_KEY belum diisi di .env server ini.')
            ->requiresConfirmation()
            ->modalHeading('Tarik data mahasiswa aktif dari SISKA')
            ->modalDescription(
                'Seluruh mahasiswa aktif di SISKA akan didaftarkan sebagai pemilih periode ini. '.
                'Setelah selesai, daftar pemilih akan sama dengan daftar mahasiswa aktif SISKA: '.
                'siapa pun yang tidak ada di daftar itu akan dikeluarkan, kecuali yang sudah mencoblos. '.
                'Suara dan data periode lama tidak tersentuh.'
            )
            ->modalSubmitActionLabel('Mulai Tarik Data')
            ->action(function () {
                if (VoterSyncRun::isRunning()) {
                    Notification::make()
                        ->title('Masih ada tarikan yang berjalan')
                        ->body('Tunggu sampai selesai, lihat progresnya di halaman Sinkronisasi SISKA.')
                        ->warning()
                        ->send();

                    return;
                }

                $run = VoterSyncRun::create([
                    'period_id' => Period::activeId(),
                    'started_by' => Auth::id(),
                    'status' => VoterSyncRun::STATUS_RUNNING,
                    'started_at' => now(),
                ]);

                ImportSiskaStudentPage::dispatch($run->id);

                Notification::make()
                    ->title('Tarikan dimulai')
                    ->body('Berjalan di latar belakang. Progres dan hasilnya ada di halaman Sinkronisasi SISKA.')
                    ->success()
                    ->send();
            });
    }
}
