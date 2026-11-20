<?php

namespace App\Filament\Resources\Paslons;

use App\Filament\Resources\Paslons\Pages\CreatePaslon;
use App\Filament\Resources\Paslons\Pages\EditPaslon;
use App\Filament\Resources\Paslons\Pages\ListPaslons;
use App\Models\Candidate;
use App\Models\Event;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaslonResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'name_candidates';

    protected static ?string $modelLabel = 'Paslon';

    protected static ?string $pluralModelLabel = 'Paslon';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        Select::make('event_id')
                            ->label('Pemilihan')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Only prefill an empty jabatan; never overwrite what an admin typed.
                                if (filled($get('position'))) {
                                    return;
                                }

                                $set('position', static::suggestPosition($state));
                            }),

                        Toggle::make('is_blank_box')
                            ->label('Kotak Kosong')
                            ->helperText('Centang kalau baris ini adalah pilihan kotak kosong. Tetap muncul di surat suara, tetapi tidak ditampilkan di halaman depan.')
                            ->live()
                            ->afterStateUpdated(function (bool $state, callable $set, callable $get) {
                                if ($state && blank($get('name_candidates'))) {
                                    $set('name_candidates', 'Kotak Kosong');
                                }
                            }),

                        TextInput::make('name_candidates')
                            ->label('Nama Paslon')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('number')
                            ->label('Nomor Urut')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->rules(['integer', 'min:1']),

                        TextInput::make('position')
                            ->label('Jabatan yang Diperebutkan')
                            ->helperText('Terisi otomatis dari nama pemilihan, dan tetap bisa diubah.')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('vision')
                            ->label('Visi')
                            ->rows(4)
                            ->required(fn (Get $get) => ! $get('is_blank_box'))
                            ->hidden(fn (Get $get) => (bool) $get('is_blank_box')),

                        Textarea::make('mission')
                            ->label('Misi')
                            ->rows(4)
                            ->required(fn (Get $get) => ! $get('is_blank_box'))
                            ->hidden(fn (Get $get) => (bool) $get('is_blank_box')),

                        FileUpload::make('image')
                            ->label('Foto Surat Suara')
                            ->helperText('Foto polos yang dipakai di halaman pemilihan.')
                            ->image()
                            ->disk('public')
                            ->directory('paslon-images')
                            ->visibility('public')
                            ->maxSize(4096)
                            ->required(fn (Get $get) => ! $get('is_blank_box'))
                            ->hidden(fn (Get $get) => (bool) $get('is_blank_box')),

                        FileUpload::make('showcase_image')
                            ->label('Foto Halaman Depan')
                            ->helperText('Desain yang sudah jadi dan siap tampil di bagian kandidat halaman depan. Kalau dikosongkan, foto surat suara yang dipakai.')
                            ->image()
                            ->disk('public')
                            ->directory('paslon-showcase')
                            ->visibility('public')
                            ->maxSize(4096)
                            ->hidden(fn (Get $get) => (bool) $get('is_blank_box')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('showcase_image')
                    ->label('Halaman Depan')
                    ->disk('public')
                    ->defaultImageUrl(fn (Candidate $record) => $record->imageUrl())
                    ->circular(),

                TextColumn::make('number')
                    ->label('No. Urut')
                    ->sortable(),

                TextColumn::make('name_candidates')
                    ->label('Nama Paslon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label('Pemilihan')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                IconColumn::make('is_blank_box')
                    ->label('Kotak Kosong')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Pemilihan')
                    ->relationship('event', 'title')
                    ->preload()
                    ->searchable(),
            ])
            ->defaultSort('event_id')
            ->defaultGroup('event.title');
    }

    protected static function suggestPosition(?int $eventId): ?string
    {
        $event = $eventId ? Event::find($eventId) : null;

        if (! $event) {
            return null;
        }

        return str_contains(strtolower($event->title), 'presiden')
            ? 'Calon '.$event->title
            : 'Calon Ketua '.$event->title;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaslons::route('/'),
            'create' => CreatePaslon::route('/create'),
            'edit' => EditPaslon::route('/{record}/edit'),
        ];
    }
}
