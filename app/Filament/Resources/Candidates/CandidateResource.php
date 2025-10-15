<?php

namespace App\Filament\Resources\Candidates;

use App\Filament\Resources\Candidates\Pages\CreateCandidate;
use App\Filament\Resources\Candidates\Pages\EditCandidate;
use App\Filament\Resources\Candidates\Pages\ListCandidates;
use App\Filament\Resources\Candidates\Schemas\CandidateForm;
use App\Filament\Resources\Candidates\Tables\CandidatesTable;
use App\Models\Candidate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Candidates';

    public static function form(Schema $schema): Schema
    {
        return CandidateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->join('events', 'events.id', '=', 'candidates.event_id')
                    ->select('candidates.*', DB::raw('(select count(*) from `votes` where `candidates`.`id` = `votes`.`candidate_id`) as `votes_count`'))
            )
            ->columns([
                TextColumn::make('name_candidates')
                    ->label('Nama Paslon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('number')
                    ->label('No. Urut')
                    ->sortable(),

                TextColumn::make('votes_count')
                    ->label('Total Suara')
                    ->sortable()
                    ->color('success')
                    ->formatStateUsing(fn(string $state): string => number_format($state)),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Filter Kategori')
                    ->relationship('event', 'title')
                    ->preload()
                    ->searchable(),
            ])
            ->groups([
                Group::make('event.title')
                    ->label('Kategori Pemilihan')
                    ->collapsible(),
            ])
            ->defaultGroup('event.title')
            ->defaultSort('events.title', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCandidates::route('/'),
            'create' => CreateCandidate::route('/create'),
            'edit' => EditCandidate::route('/{record}/edit'),
        ];
    }

    protected static ?string $modelLabel = 'Paslon';

    protected static ?string $pluralModelLabel = 'Data Paslon & Hasil Suara';
}
