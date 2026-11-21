<?php

namespace App\Filament\Resources\Voters\Pages;

use App\Filament\Resources\Voters\VoterResource;
use App\Imports\VoterImport;
use App\Models\Period;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListVoters extends ListRecords
{
    protected static string $resource = VoterResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
}
