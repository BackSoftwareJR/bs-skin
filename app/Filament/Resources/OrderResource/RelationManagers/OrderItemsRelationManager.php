<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $title = 'Articoli Ordinati';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                Tables\Columns\ImageColumn::make('product_variant.product.featured_image')
                    ->label('Immagine')
                    ->circular()
                    ->size(48)
                    ->placeholder('heroicon-o-photo'),
                    
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Prodotto')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('variant_name')
                    ->label('Variante')
                    ->placeholder('Nessuna')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->fontFamily('mono')
                    ->copyable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantità')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Prezzo Unitario')
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tax_rate')
                    ->label('IVA%')
                    ->formatStateUsing(fn ($state): string => $state ? number_format($state * 100, 0) . '%' : '0%')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Totale')
                    ->money('EUR')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Nessuna azione di create - i prodotti sono immutabili
            ])
            ->actions([
                // Nessuna azione di edit/delete - i prodotti sono immutabili
            ])
            ->bulkActions([
                // Nessuna azione bulk
            ])
            ->paginated(false); // Mostra tutti gli item senza paginazione
    }
    
    public function isReadOnly(): bool
    {
        return true;
    }
}