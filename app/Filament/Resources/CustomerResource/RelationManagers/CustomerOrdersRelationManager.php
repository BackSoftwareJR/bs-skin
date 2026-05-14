<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;

class CustomerOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Ordini Cliente';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Numero Ordine')
                    ->fontFamily('mono')
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record])),
                    
                Tables\Columns\TextColumn::make('total')
                    ->label('Totale')
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato Ordine')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->getLabel())
                    ->color(fn (OrderStatus $state): string => $state->getColor()),
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Stato Pagamento')
                    ->badge()
                    ->formatStateUsing(fn (PaymentStatus $state): string => $state->getLabel())
                    ->color(fn (PaymentStatus $state): string => $state->getColor()),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data Ordine')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato Ordine')
                    ->options(OrderStatus::class),
                    
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Stato Pagamento')
                    ->options(PaymentStatus::class),
            ])
            ->headerActions([
                // Nessuna azione di create - gli ordini si creano dal frontend
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Visualizza')
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                // Nessuna azione bulk
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public function isReadOnly(): bool
    {
        return true;
    }
}