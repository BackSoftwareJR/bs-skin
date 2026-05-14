<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\PaymentStatus;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pagamenti';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('external_id')
            ->columns([
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'stripe' => 'blue',
                        'paypal' => 'yellow',
                        'manual' => 'gray',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('method')
                    ->label('Metodo')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('external_id')
                    ->label('ID Esterno')
                    ->fontFamily('mono')
                    ->limit(20)
                    ->copyable()
                    ->tooltip(function ($record) {
                        return $record->external_id;
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (PaymentStatus $state): string => $state->getLabel())
                    ->color(fn (PaymentStatus $state): string => $state->getColor()),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('Importo')
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Pagato il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('--'),
                    
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Errore')
                    ->placeholder('--')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->error_message;
                    })
                    ->color('danger')
                    ->visible(fn ($record): bool => !empty($record->error_message)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options(PaymentStatus::class),
                    
                Tables\Filters\SelectFilter::make('provider')
                    ->label('Provider')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'manual' => 'Manuale',
                    ]),
            ])
            ->headerActions([
                // Nessuna azione di create - i pagamenti sono gestiti dal sistema
            ])
            ->actions([
                // Nessuna azione di edit/delete - i pagamenti sono immutabili
            ])
            ->bulkActions([
                // Nessuna azione bulk
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false); // Mostra tutti i pagamenti senza paginazione
    }
    
    public function isReadOnly(): bool
    {
        return true;
    }
}