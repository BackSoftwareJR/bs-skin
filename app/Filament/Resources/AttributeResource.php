<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeResource\Pages;
use App\Filament\Resources\AttributeResource\RelationManagers\AttributeValuesRelationManager;
use App\Models\Attribute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Catalogo';
    
    protected static ?string $navigationLabel = 'Attributi';
    
    protected static ?string $modelLabel = 'Attributo';
    
    protected static ?string $pluralModelLabel = 'Attributi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Codice')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^[a-z0-9_]+$/'])
                    ->helperText('Solo lettere minuscole, numeri e underscore')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('name.it')
                    ->label('Nome (IT)')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'select' => 'Selezione singola',
                        'multiselect' => 'Selezione multipla',
                        'text' => 'Testo',
                        'number' => 'Numero',
                        'boolean' => 'Booleano',
                        'color' => 'Colore',
                        'range' => 'Intervallo'
                    ]),
                    
                Forms\Components\Toggle::make('is_filterable')
                    ->label('Filtrabile')
                    ->default(false),
                    
                Forms\Components\Toggle::make('is_required')
                    ->label('Obbligatorio')
                    ->default(false),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name.it')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'select' => 'Selezione',
                        'multiselect' => 'Multi-selezione',
                        'text' => 'Testo',
                        'number' => 'Numero',
                        'boolean' => 'Booleano',
                        'color' => 'Colore',
                        'range' => 'Intervallo',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'select' => 'info',
                        'multiselect' => 'warning',
                        'text' => 'gray',
                        'number' => 'success',
                        'boolean' => 'primary',
                        'color' => 'purple',
                        'range' => 'orange',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('is_filterable')
                    ->label('Filtrabile')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
    
    public static function getRelations(): array
    {
        return [
            AttributeValuesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}