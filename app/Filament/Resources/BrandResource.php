<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Marchi';
    
    protected static ?string $modelLabel = 'marchio';
    
    protected static ?string $pluralModelLabel = 'marchi';
    
    protected static ?string $navigationGroup = 'Catalogo';
    
    protected static ?int $navigationSort = 30;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Base')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Marchio')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('slug', str()->slug($state))
                            ),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->rules(['regex:/^[a-z0-9\-]+$/']),
                        
                        Forms\Components\TextInput::make('website_url')
                            ->label('Sito Web')
                            ->url()
                            ->maxLength(500),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Ordine di Visualizzazione')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Descrizione e Media')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Descrizione')
                            ->columnSpanFull(),
                        
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo')
                            ->collection('logo')
                            ->image()
                            ->imageEditor()
                            ->required(),
                    ]),
                
                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60),
                        
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160),
                        
                        Forms\Components\TextInput::make('og_title')
                            ->label('OG Title (Facebook)')
                            ->maxLength(60),
                        
                        Forms\Components\Textarea::make('og_description')
                            ->label('OG Description (Facebook)')
                            ->rows(2)
                            ->maxLength(160),
                        
                        SpatieMediaLibraryFileUpload::make('og_image')
                            ->label('OG Image')
                            ->collection('og_image')
                            ->image(),
                        
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url(),
                        
                        Forms\Components\Select::make('robots')
                            ->label('Robots')
                            ->options([
                                'index,follow' => 'index,follow',
                                'noindex,follow' => 'noindex,follow',
                                'index,nofollow' => 'index,nofollow',
                                'noindex,nofollow' => 'noindex,nofollow',
                            ])
                            ->default('index,follow'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->width(50)
                    ->height(50),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('website_url')
                    ->label('Sito Web')
                    ->url(fn (Brand $record): ?string => $record->website_url)
                    ->openUrlInNewTab()
                    ->limit(50)
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Prodotti')
                    ->counts('products')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Stato')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo disattivi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Attiva')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_active' => true])
                        ),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Disattiva')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_active' => false])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'view' => Pages\ViewBrand::route('/{record}'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}