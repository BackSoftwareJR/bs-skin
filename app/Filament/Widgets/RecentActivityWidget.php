<?php

namespace App\Filament\Widgets;

use Spatie\Activitylog\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Attività Recente';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::with(['causer'])
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'order' => 'Ordine',
                        'product' => 'Prodotto',
                        'customer' => 'Cliente',
                        'user' => 'Utente',
                        default => ucfirst($state),
                    }),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrizione')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Oggetto')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) return '-';
                        return class_basename($state);
                    }),
                    
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Utente')
                    ->placeholder('Sistema')
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Quando')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->modalContent(function (Activity $record): string {
                        return view('filament.widgets.activity-details', [
                            'activity' => $record,
                        ])->render();
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Chiudi'),
            ]);
    }
}