<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Filament\Resources\FeedbackResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class FeedbackRelationManager extends RelationManager
{
    protected static string $relationship = 'feedback';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Submitted')->dateTime()->since()->description(fn ($record) => $record->created_at)->sortable(),
                Tables\Columns\TextColumn::make('form.name')->label('Form'),
                Tables\Columns\TextColumn::make('position.response')->label('Position'),
                Tables\Columns\TextColumn::make('submitter')->label('Submitted By'),
                Tables\Columns\TextColumn::make('actioner')->label('Actioned By'),
            ])->defaultSort('created_at', 'asc')
            ->actions([
                Tables\Actions\ViewAction::make()->resource(FeedbackResource::class),
            ]);
    }
}
