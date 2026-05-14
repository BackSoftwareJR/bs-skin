<?php

namespace App\Filament\Resources\PageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';
    
    protected static ?string $recordTitleAttribute = 'type';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->label('Tipo Blocco')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('content')
                    ->label('Contenuto')
                    ->rows(4)
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('content')
                    ->label('Contenuto')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->content;
                    }),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }
}