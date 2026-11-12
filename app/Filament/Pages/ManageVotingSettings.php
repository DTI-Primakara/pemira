<?php

namespace App\Filament\Pages;

use App\Models\Config;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class ManageVotingSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static ?string $title = 'Pengaturan PEMIRA';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 90;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            Config::VOTE_COUNT_VISIBLE => Config::voteCountVisible(),
            Config::VOTING_STARTS_AT => Config::get(Config::VOTING_STARTS_AT),
            Config::VOTING_ENDS_AT => Config::get(Config::VOTING_ENDS_AT),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Jadwal Voting')
                ->description('Di luar rentang ini, halaman voting tertutup otomatis. Waktu mengikuti zona '.Config::TIMEZONE.' (WITA).')
                ->schema([
                    DateTimePicker::make(Config::VOTING_STARTS_AT)
                        ->label('Voting dibuka')
                        ->seconds(false)
                        ->native(false)
                        ->required(),
                    DateTimePicker::make(Config::VOTING_ENDS_AT)
                        ->label('Voting ditutup')
                        ->seconds(false)
                        ->native(false)
                        ->required()
                        ->after(Config::VOTING_STARTS_AT),
                ])
                ->columns(2),

            Section::make('Hasil Sementara')
                ->description('Menentukan apakah perolehan suara ditampilkan di halaman depan.')
                ->schema([
                    Toggle::make(Config::VOTE_COUNT_VISIBLE)
                        ->label('Tampilkan hasil sementara di homepage')
                        ->helperText('Matikan selama masa tenang agar perolehan suara tidak memengaruhi pemilih.')
                        ->inline(false),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Config::set(Config::VOTING_STARTS_AT, $data[Config::VOTING_STARTS_AT]);
        Config::set(Config::VOTING_ENDS_AT, $data[Config::VOTING_ENDS_AT]);
        Config::set(Config::VOTE_COUNT_VISIBLE, $data[Config::VOTE_COUNT_VISIBLE] ? 'true' : 'false');

        Notification::make()
            ->title('Pengaturan tersimpan')
            ->success()
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([$this->getFormActionsContentComponent()]),
        ]);
    }

    public function getFormActionsContentComponent(): Component
    {
        return Actions::make([
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save'),
        ])->key('form-actions');
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
