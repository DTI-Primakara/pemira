<?php

namespace App\Filament\Resources\Prodis;

use App\Filament\Resources\Prodis\Pages\ListProdis;
use App\Models\Prodi;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Program Studi';

    protected static ?string $pluralModelLabel = 'Program Studi';

    protected static ?int $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('id_prodi')
                    ->label('Kode Prodi')
                    ->helperText('Empat digit yang muncul pada NIM, contoh 0101. Menentukan hak suara HIMA mahasiswa.')
                    ->required()
                    ->length(4)
                    ->rules(['regex:/^\d{4}$/'])
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),

                TextInput::make('short_name')
                    ->label('Singkatan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name')
                    ->label('Nama Program Studi')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('faculty')
                    ->label('Fakultas')
                    ->helperText('Dipakai untuk mengelompokkan hasil suara di halaman depan.')
                    ->maxLength(255),

                TextInput::make('faculty_short')
                    ->label('Singkatan Fakultas')
                    ->maxLength(255),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_prodi')->label('Kode')->sortable()->searchable(),
                TextColumn::make('name')->label('Program Studi')->sortable()->searchable(),
                TextColumn::make('short_name')->label('Singkatan'),
                TextColumn::make('faculty')->label('Fakultas')->sortable()->searchable()->placeholder('—'),
                TextColumn::make('events_count')->label('Pemilihan')->counts('events')->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('id_prodi');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProdis::route('/'),
        ];
    }
}
