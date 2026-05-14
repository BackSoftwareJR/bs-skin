<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 1;
    
    protected static ?string $heading = 'Ultimi Ordini';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::with(['customer'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Ordine')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Cliente')
                    ->limit(25)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('total')
                    ->label('Totale')
                    ->money('EUR'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => 
                        route('filament.admin.resources.orders.view', $record)
                    ),
            ]);
    }
}