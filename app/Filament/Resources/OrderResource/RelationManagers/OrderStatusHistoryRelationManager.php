<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\OrderStatus;

class OrderStatusHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistory';

    protected static ?string $title = 'Storico Stato';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('to_status')
            ->columns([
                Tables\Columns\TextColumn::make('from_status')
                    ->label('Da')
                    ->badge()
                    ->placeholder('--')
                    ->formatStateUsing(fn (?OrderStatus $state): string => 
                        $state ? $state->getLabel() : '--'
                    )
                    ->color(fn (?OrderStatus $state): string => 
                        $state ? $state->getColor() : 'gray'
                    ),
                    
                Tables\Columns\TextColumn::make('arrow')
                    ->label('')
                    ->state('→')
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('to_status')
                    ->label('A')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->getLabel())
                    ->color(fn (OrderStatus $state): string => $state->getColor()),
                    
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->placeholder('--')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->reason;
                    }),
                    
                Tables\Columns\TextColumn::make('performed_by_user.name')
                    ->label('Eseguito da')
                    ->placeholder('Sistema')
                    ->formatStateUsing(fn (?string $state): string => $state ?? 'Sistema'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Nessuna azione di create - lo storico è gestito automaticamente
            ])
            ->actions([
                // Nessuna azione di edit/delete - lo storico è immutabile
            ])
            ->bulkActions([
                // Nessuna azione bulk
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false); // Mostra tutto lo storico senza paginazione
    }
    
    public function isReadOnly(): bool
    {
        return true;
    }
}