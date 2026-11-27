<?php

namespace App\Filament\Resources\Voters;

use App\Filament\Resources\Voters\Pages\CreateVoter;
use App\Filament\Resources\Voters\Pages\EditVoter;
use App\Filament\Resources\Voters\Pages\ListVoters;
use App\Models\Period;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class VoterResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'voter';

    // Without these the menu reads "Users", derived from the User model — the
    // only English entry in an otherwise Indonesian panel, and ambiguous next
    // to the Roles resource that sits in a navigation group of the same name.
    protected static ?string $modelLabel = 'Pemilih';

    protected static ?string $pluralModelLabel = 'Pemilih';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->numeric()
                            ->minLength(10)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->maxLength(255)
                            ->email(),
                        Hidden::make('roles')
                            ->default(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $periodId = Period::activeId();

                return $query
                    // The ballot shown is the one for the period being run now.
                    ->leftJoin('user_vote', function ($join) use ($periodId) {
                        $join->on('user_vote.user_id', '=', 'users.id')
                            ->where('user_vote.period_id', '=', $periodId);
                    })
                    // Coordinators are voters too, so they must not drop off this list.
                    ->whereIn('users.roles', [User::ROLE_PARTICIPANT, User::ROLE_KORTI])
                    ->select([
                        'users.*',
                        'user_vote.status as vote_status',
                    ]);
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->placeholder('Belum ada kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('vote_status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(
                        query: function ($query, string $search) {
                            $query->orWhere('user_vote.status', 'like', "%{$search}%");
                        }
                    )
                    ->default('Not Login')
                    ->formatStateUsing(fn ($state) => Str::title($state)),
            ])
            ->filters([
                SelectFilter::make('class_id')
                    ->label('Kelas')
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    /** Only the people on the roll of the period being run now. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('periods', fn (Builder $query) => $query->whereKey(Period::activeId()));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVoters::route('/'),
            'create' => CreateVoter::route('/create'),
            'edit' => EditVoter::route('/{record}/edit'),
        ];
    }
}
