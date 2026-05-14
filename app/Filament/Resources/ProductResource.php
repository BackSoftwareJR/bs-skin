<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationLabel = 'Prodotti';
    
    protected static ?string $modelLabel = 'prodotto';
    
    protected static ?string $pluralModelLabel = 'prodotti';
    
    protected static ?string $navigationGroup = 'Catalogo';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Product Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Generale')
                            ->schema([
                                Forms\Components\Section::make('Informazioni Base')
                                    ->schema([
                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100),
                                        
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nome Prodotto')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\Select::make('product_type')
                                            ->label('Tipo Prodotto')
                                            ->options(ProductType::class)
                                            ->required()
                                            ->native(false)
                                            ->reactive(),
                                        
                                        Forms\Components\Select::make('status')
                                            ->label('Stato')
                                            ->options(ProductStatus::class)
                                            ->required()
                                            ->default('draft')
                                            ->native(false),
                                        
                                        Forms\Components\Select::make('brand_id')
                                            ->label('Brand')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload(),
                                        
                                        Forms\Components\CheckboxList::make('categories')
                                            ->label('Categorie')
                                            ->relationship('categories', 'name')
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Prezzi e Tasse')
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Prezzo')
                                            ->required()
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01),
                                        
                                        Forms\Components\TextInput::make('compare_at_price')
                                            ->label('Prezzo di Confronto')
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01),
                                        
                                        Forms\Components\TextInput::make('tax_rate')
                                            ->label('Aliquota IVA (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->step(0.01),
                                    ])
                                    ->columns(3),
                                
                                Forms\Components\Section::make('Descrizioni')
                                    ->schema([
                                        Forms\Components\Textarea::make('short_description')
                                            ->label('Descrizione Breve')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Descrizione Completa')
                                            ->columnSpanFull(),
                                    ]),
                                
                                // Campi condizionali per tipo prodotto
                                Forms\Components\Section::make('Dettagli Cosmetici')
                                    ->schema([
                                        Forms\Components\Textarea::make('ingredients_text')
                                            ->label('Ingredienti')
                                            ->rows(4),
                                        
                                        Forms\Components\Textarea::make('inci_text')
                                            ->label('INCI')
                                            ->rows(4),
                                        
                                        Forms\Components\RichEditor::make('usage_instructions')
                                            ->label('Istruzioni d\'uso'),
                                    ])
                                    ->columns(1)
                                    ->visible(fn (Forms\Get $get): bool => $get('product_type') === 'cosmetic'),
                                
                                Forms\Components\Section::make('Dettagli Device')
                                    ->schema([
                                        Forms\Components\Repeater::make('technical_specs_json')
                                            ->label('Specifiche Tecniche')
                                            ->schema([
                                                Forms\Components\TextInput::make('key')
                                                    ->label('Caratteristica')
                                                    ->required(),
                                                Forms\Components\TextInput::make('value')
                                                    ->label('Valore')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(1),
                                        
                                        Forms\Components\Repeater::make('certifications_json')
                                            ->label('Certificazioni')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nome Certificazione')
                                                    ->required(),
                                                Forms\Components\TextInput::make('code')
                                                    ->label('Codice'),
                                            ])
                                            ->columns(2),
                                        
                                        Forms\Components\TextInput::make('warranty_months')
                                            ->label('Garanzia (mesi)')
                                            ->numeric()
                                            ->minValue(0),
                                        
                                        Forms\Components\TextInput::make('video_demo_url')
                                            ->label('Video Demo URL')
                                            ->url(),
                                        
                                        SpatieMediaLibraryFileUpload::make('manual_pdf')
                                            ->label('Manuale PDF')
                                            ->collection('manual_pdf')
                                            ->acceptedFileTypes(['application/pdf']),
                                        
                                        Forms\Components\Toggle::make('is_rentable')
                                            ->label('Noleggiabile')
                                            ->reactive(),
                                        
                                        Forms\Components\TextInput::make('rental_daily_price')
                                            ->label('Prezzo Giornaliero Noleggio')
                                            ->numeric()
                                            ->prefix('€')
                                            ->visible(fn (Forms\Get $get): bool => $get('is_rentable')),
                                        
                                        Forms\Components\TextInput::make('rental_monthly_price')
                                            ->label('Prezzo Mensile Noleggio')
                                            ->numeric()
                                            ->prefix('€')
                                            ->visible(fn (Forms\Get $get): bool => $get('is_rentable')),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (Forms\Get $get): bool => $get('product_type') === 'device'),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Media')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->label('Immagine in Evidenza')
                                    ->collection('featured_image')
                                    ->image()
                                    ->imageEditor(),
                                
                                SpatieMediaLibraryFileUpload::make('gallery')
                                    ->label('Galleria Immagini')
                                    ->collection('gallery')
                                    ->image()
                                    ->imageEditor()
                                    ->multiple()
                                    ->reorderable(),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60),
                                        
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3)
                                            ->maxLength(160),
                                        
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url(),
                                    ])
                                    ->columns(1),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Impostazioni')
                            ->schema([
                                Forms\Components\Section::make('Badge e Etichette')
                                    ->schema([
                                        Forms\Components\TextInput::make('badge_label')
                                            ->label('Etichetta Badge')
                                            ->maxLength(50),
                                        
                                        Forms\Components\ColorPicker::make('badge_color')
                                            ->label('Colore Badge'),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Flags Prodotto')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('In Evidenza'),
                                        
                                        Forms\Components\Toggle::make('is_new')
                                            ->label('Novità'),
                                        
                                        Forms\Components\Toggle::make('is_bestseller')
                                            ->label('Bestseller'),
                                        
                                        Forms\Components\Toggle::make('requires_shipping')
                                            ->label('Richiede Spedizione')
                                            ->default(true),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Date e Pubblicazione')
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Data Pubblicazione'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Immagine')
                    ->collection('featured_image')
                    ->width(60)
                    ->height(60),
                
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Prezzo')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('inventory_summary')
                    ->label('Stock')
                    ->getStateUsing(function (Product $record): string {
                        $total = $record->variants->sum('inventory.quantity');
                        $low = $record->variants->filter(fn($v) => 
                            $v->inventory && $v->inventory->quantity <= $v->inventory->threshold_low
                        )->count();
                        
                        $color = $low > 0 ? 'text-warning-600' : 'text-success-600';
                        return "<span class='{$color}'>{$total}</span>";
                    })
                    ->html(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options(ProductStatus::class),
                
                Tables\Filters\SelectFilter::make('brand')
                    ->label('Brand')
                    ->relationship('brand', 'name'),
                
                Tables\Filters\SelectFilter::make('product_type')
                    ->label('Tipo Prodotto')
                    ->options(ProductType::class),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('In Evidenza'),
                
                Tables\Filters\TernaryFilter::make('is_new')
                    ->label('Novità'),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplica')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Product $record) {
                        $newProduct = $record->replicate();
                        $newProduct->sku = $record->sku . '-copy';
                        $newProduct->status = 'draft';
                        $newProduct->save();
                        
                        return redirect()->route('filament.admin.resources.products.edit', $newProduct);
                    }),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Pubblica')
                        ->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['status' => 'published'])
                        ),
                    
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archivia')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['status' => 'archived'])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}