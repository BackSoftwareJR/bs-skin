<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class AuditLogPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static string $view = 'filament.pages.audit-log-page';
    
    protected static ?string $navigationGroup = 'Sistema';
    
    protected static ?string $navigationLabel = 'Log Attività';
    
    protected static ?string $title = 'Log Attività Sistema';

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query())
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Categoria')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'default' => 'gray',
                        'user' => 'blue',
                        'order' => 'green',
                        'product' => 'purple',
                        'customer' => 'orange',
                        'system' => 'red',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrizione')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->description;
                    })
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Utente')
                    ->placeholder('Sistema')
                    ->formatStateUsing(fn (?string $state): string => $state ?? 'Sistema')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Soggetto')
                    ->badge()
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) return '--';
                        
                        $parts = explode('\\', $state);
                        return end($parts);
                    })
                    ->color('info')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID Soggetto')
                    ->fontFamily('mono')
                    ->placeholder('--')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Categoria')
                    ->options([
                        'default' => 'Default',
                        'user' => 'Utente',
                        'order' => 'Ordine',
                        'product' => 'Prodotto',
                        'customer' => 'Cliente',
                        'system' => 'Sistema',
                    ]),
                    
                Tables\Filters\Filter::make('causer_id')
                    ->form([
                        Tables\Filters\Components\TextInput::make('causer_name')
                            ->label('Nome Utente')
                            ->placeholder('Filtra per nome utente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['causer_name'],
                            fn (Builder $query, $name): Builder => $query->whereHas(
                                'causer',
                                fn (Builder $query): Builder => $query->where('name', 'like', "%{$name}%")
                            )
                        );
                    }),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Tables\Filters\Components\DatePicker::make('created_from')
                            ->label('Dal'),
                        Tables\Filters\Components\DatePicker::make('created_until')
                            ->label('Al'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Dettagli')
                    ->modalHeading('Dettagli Log Attività')
                    ->modalContent(fn (Activity $record): string => view('filament.modals.activity-log-details', ['record' => $record])->render()),
            ])
            ->bulkActions([
                // Nessuna azione bulk - i log sono read-only
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->paginated([25, 50, 100, 'all']);
    }
}