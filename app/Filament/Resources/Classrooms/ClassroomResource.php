<?php

namespace App\Filament\Resources\Classrooms;

use App\Filament\Resources\Classrooms\Pages\ListClassrooms;
use App\Filament\Resources\Classrooms\Pages\ManageClassroomMembers;
use App\Models\Classroom;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(1)->schema([
                TextInput::make('name')
                    ->label('Nama Kelas')
                    ->placeholder('Contoh: SI-3A')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Textarea::make('bulk_nim')
                    ->label('Tambah Anggota dengan NIM')
                    ->rows(6)
                    ->placeholder("2301010001\n2301010002\n2301010003")
                    ->helperText('Tempel daftar NIM, dipisahkan baris baru, koma, atau spasi. NIM yang tidak ditemukan akan dilaporkan.'),

                Select::make('korti_user_id')
                    ->label('Koordinator Kelas (Korti)')
                    ->placeholder('Belum ditunjuk')
                    ->options(fn (?Classroom $record) => $record
                        ? $record->members()->orderBy('name')->pluck('name', 'id')
                        : [])
                    ->searchable()
                    ->helperText('Hanya anggota kelas ini yang dapat ditunjuk. Korti mendapat akses ke halaman pantauan voting kelasnya.')
                    ->visible(fn (?Classroom $record) => $record !== null),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('korti.name')
                    ->label('Korti')
                    ->placeholder('Belum ditunjuk')
                    ->searchable(),

                TextColumn::make('members_count')
                    ->label('Anggota')
                    ->counts('members')
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make()->label('Kelola'),
                DeleteAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassrooms::route('/'),
            'edit' => ManageClassroomMembers::route('/{record}/edit'),
        ];
    }
}
