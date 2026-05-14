<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\MenuItem;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'menuItems';

    protected static ?string $title = 'Voci del Menu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label.it')
                    ->label('Etichetta (IT)')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'page' => 'Pagina',
                        'category' => 'Categoria',
                        'brand' => 'Brand',
                        'product' => 'Prodotto',
                        'blog' => 'Blog',
                        'custom' => 'URL Personalizzato',
                    ])
                    ->live(),
                    
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->visible(fn (Forms\Get $get): bool => $get('type') === 'custom')
                    ->url()
                    ->maxLength(500),
                    
                Forms\Components\TextInput::make('icon')
                    ->label('Icona')
                    ->placeholder('heroicon-o-home')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('badge_label')
                    ->label('Etichetta Badge')
                    ->maxLength(50),
                    
                Forms\Components\Toggle::make('opens_in_new_tab')
                    ->label('Apri in nuova scheda')
                    ->default(false),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Attiva')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label.it')
            ->columns([
                Tables\Columns\TextColumn::make('label.it')
                    ->label('Etichetta')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'page' => 'Pagina',
                        'category' => 'Categoria',
                        'brand' => 'Brand',
                        'product' => 'Prodotto',
                        'blog' => 'Blog',
                        'custom' => 'Personalizzato',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'page' => 'info',
                        'category' => 'success',
                        'brand' => 'warning',
                        'product' => 'primary',
                        'blog' => 'purple',
                        'custom' => 'gray',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->url;
                    })
                    ->visible(fn ($record) => $record->type === 'custom'),
                    
                Tables\Columns\TextColumn::make('parent.label.it')
                    ->label('Genitore')
                    ->placeholder('Nessuno')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Attiva'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'page' => 'Pagina',
                        'category' => 'Categoria',
                        'brand' => 'Brand',
                        'product' => 'Prodotto',
                        'blog' => 'Blog',
                        'custom' => 'Personalizzato',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attiva')
                    ->placeholder('Tutte')
                    ->trueLabel('Solo attive')
                    ->falseLabel('Solo inattive'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Aggiungi Voce'),
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
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}