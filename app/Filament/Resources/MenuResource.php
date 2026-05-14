<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers\MenuItemsRelationManager;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    
    protected static ?string $navigationGroup = 'Contenuti';
    
    protected static ?string $navigationLabel = 'Menu';
    
    protected static ?string $modelLabel = 'Menu';
    
    protected static ?string $pluralModelLabel = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Codice')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^[a-z0-9-_]+$/'])
                    ->helperText('Solo lettere minuscole, numeri, trattini e underscore')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('location')
                    ->label('Posizione')
                    ->required()
                    ->options([
                        'main_nav' => 'Navigazione Principale',
                        'footer_nav' => 'Footer',
                        'mobile_nav' => 'Mobile',
                        'custom' => 'Personalizzato',
                    ]),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true),
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
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('location')
                    ->label('Posizione')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'main_nav' => 'Navigazione Principale',
                        'footer_nav' => 'Footer',
                        'mobile_nav' => 'Mobile',
                        'custom' => 'Personalizzato',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'main_nav' => 'primary',
                        'footer_nav' => 'info',
                        'mobile_nav' => 'warning',
                        'custom' => 'gray',
                        default => 'gray',
                    }),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Attivo'),
                    
                Tables\Columns\TextColumn::make('menu_items_count')
                    ->label('Voci')
                    ->counts('menuItems')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->label('Posizione')
                    ->options([
                        'main_nav' => 'Navigazione Principale',
                        'footer_nav' => 'Footer',
                        'mobile_nav' => 'Mobile',
                        'custom' => 'Personalizzato',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attivo')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo inattivi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            MenuItemsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}