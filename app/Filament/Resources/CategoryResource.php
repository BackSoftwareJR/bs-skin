<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    
    protected static ?string $navigationLabel = 'Categorie';
    
    protected static ?string $modelLabel = 'categoria';
    
    protected static ?string $pluralModelLabel = 'categorie';
    
    protected static ?string $navigationGroup = 'Catalogo';
    
    protected static ?int $navigationSort = 20;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Base')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Categoria Genitore')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Categoria')
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
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options(CategoryType::class)
                            ->required()
                            ->native(false)
                            ->helperText('Macroarea = categoria principale, Microarea = sottocategoria specifica'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Attiva')
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
                        Forms\Components\Textarea::make('description')
                            ->label('Descrizione')
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        SpatieMediaLibraryFileUpload::make('cover_image')
                            ->label('Immagine di Copertina')
                            ->collection('cover')
                            ->image()
                            ->imageEditor(),
                        
                        SpatieMediaLibraryFileUpload::make('icon')
                            ->label('Icona Categoria')
                            ->collection('icon')
                            ->image()
                            ->imageEditor(),
                    ])
                    ->columns(2),
                
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover_image')
                    ->label('Immagine')
                    ->collection('cover')
                    ->width(50)
                    ->height(50),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (string $state, Category $record): string {
                        $indent = str_repeat('— ', $record->depth ?? 0);
                        return $indent . $state;
                    }),
                
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Genitore')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'macroarea' => 'info',
                        'microarea' => 'success',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attiva')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Prodotti')
                    ->counts('products')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(CategoryType::class),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Stato')
                    ->trueLabel('Solo attive')
                    ->falseLabel('Solo disattive'),
                
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Categoria Genitore')
                    ->relationship('parent', 'name'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}