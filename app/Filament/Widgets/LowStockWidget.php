<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use App\Models\ProductVariant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Schema;

class LowStockWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 1;
    
    protected static ?string $heading = 'Stock Sotto Soglia';

    public static function canView(): bool
    {
        return Schema::hasTable('inventory') && Schema::hasTable('product_variants');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::with(['product', 'inventory'])
                    ->whereHas('inventory', function ($query) {
                        $query->whereColumn('quantity', '<=', 'threshold_low');
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Prodotto')
                    ->limit(30)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Variante')
                    ->limit(20)
                    ->placeholder('Default'),
                    
                Tables\Columns\TextColumn::make('inventory.quantity')
                    ->label('Quantità')
                    ->badge()
                    ->color(fn (ProductVariant $record): string => 
                        ($record->inventory->quantity ?? 0) <= 0 ? 'danger' : 'warning'
                    ),
                    
                Tables\Columns\TextColumn::make('inventory.threshold_low')
                    ->label('Soglia')
                    ->placeholder('Non impostata'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (ProductVariant $record): string => 
                        route('filament.admin.resources.products.edit', $record->product_id)
                    ),
            ]);
    }
}