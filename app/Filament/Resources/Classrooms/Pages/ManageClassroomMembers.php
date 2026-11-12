<?php

namespace App\Filament\Resources\Classrooms\Pages;

use App\Filament\Resources\Classrooms\ClassroomResource;
use App\Models\Classroom;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class ManageClassroomMembers extends EditRecord
{
    protected static string $resource = ClassroomResource::class;

    protected static ?string $title = 'Kelola Kelas';

    private ?string $pendingNims = null;

    private int|string|null $pendingKortiId = null;

    private bool $kortiWasSubmitted = false;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Neither of these is a plain column write: one resolves student numbers,
        // the other also moves a role between users.
        $this->pendingNims = $data['bulk_nim'] ?? null;

        if (array_key_exists('korti_user_id', $data)) {
            $this->kortiWasSubmitted = true;
            $this->pendingKortiId = $data['korti_user_id'];
        }

        unset($data['bulk_nim'], $data['korti_user_id']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Classroom $classroom */
        $classroom = $this->getRecord();

        if (filled($this->pendingNims)) {
            $this->reportMembership($classroom->addMembersByNim($this->pendingNims));
            $this->pendingNims = null;
        }

        if ($this->kortiWasSubmitted) {
            $kortiId = $this->pendingKortiId !== null ? (int) $this->pendingKortiId : null;

            if ((int) $classroom->korti_user_id !== (int) $kortiId) {
                $classroom->appointKorti($kortiId);
            }

            $this->kortiWasSubmitted = false;
        }

        $this->refreshFormData(['korti_user_id']);
    }

    /**
     * @param  array{added: int, moved: int, already: int, missing: array<int, string>}  $report
     */
    private function reportMembership(array $report): void
    {
        $lines = [];

        if ($report['added'] > 0) {
            $lines[] = "{$report['added']} mahasiswa ditambahkan.";
        }

        if ($report['moved'] > 0) {
            $lines[] = "{$report['moved']} dipindahkan dari kelas lain.";
        }

        if ($report['already'] > 0) {
            $lines[] = "{$report['already']} sudah terdaftar di kelas ini.";
        }

        if ($report['missing'] !== []) {
            $missing = implode(', ', array_slice($report['missing'], 0, 10));
            $suffix = count($report['missing']) > 10 ? ', ...' : '';
            $lines[] = 'NIM tidak ditemukan: '.$missing.$suffix;
        }

        $notification = Notification::make()
            ->title($lines === [] ? 'Tidak ada perubahan anggota' : 'Anggota kelas diperbarui')
            ->body(implode(' ', $lines));

        $report['missing'] !== [] ? $notification->warning() : $notification->success();

        $notification->send();
    }
}
