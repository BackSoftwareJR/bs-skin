<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;

class ShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    protected static ?string $title = 'Spedizioni';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('carrier')
                    ->label('Corriere')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('tracking_number')
                    ->label('Codice di Tracciamento')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('tracking_url')
                    ->label('URL di Tracciamento')
                    ->url()
                    ->maxLength(500)
                    ->helperText('Link completo per tracciare la spedizione'),
                    
                Forms\Components\Select::make('status')
                    ->label('Stato')
                    ->required()
                    ->options([
                        'pending' => 'In preparazione',
                        'shipped' => 'Spedito',
                        'in_transit' => 'In transito',
                        'delivered' => 'Consegnato',
                        'failed' => 'Fallito',
                        'returned' => 'Restituito',
                    ])
                    ->default('pending'),
                    
                Forms\Components\DatePicker::make('shipped_at')
                    ->label('Data Spedizione')
                    ->native(false),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Note')
                    ->rows(3)
                    ->maxLength(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tracking_number')
            ->columns([
                Tables\Columns\TextColumn::make('carrier')
                    ->label('Corriere')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Codice Tracciamento')
                    ->fontFamily('mono')
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record, $state) {
                        if ($record->tracking_url) {
                            return $state . ' 🔗';
                        }
                        return $state;
                    })
                    ->tooltip('Clicca per copiare'),
                    
                Tables\Columns\SelectColumn::make('status')
                    ->label('Stato')
                    ->options([
                        'pending' => 'In preparazione',
                        'shipped' => 'Spedito',
                        'in_transit' => 'In transito',
                        'delivered' => 'Consegnato',
                        'failed' => 'Fallito',
                        'returned' => 'Restituito',
                    ]),
                    
                Tables\Columns\TextColumn::make('shipped_at')
                    ->label('Data Spedizione')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('--'),
                    
                Tables\Columns\TextColumn::make('delivered_at')
                    ->label('Data Consegna')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('--'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'pending' => 'In preparazione',
                        'shipped' => 'Spedito',
                        'in_transit' => 'In transito',
                        'delivered' => 'Consegnato',
                        'failed' => 'Fallito',
                        'returned' => 'Restituito',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuova Spedizione'),
            ])
            ->actions([
                Action::make('track')
                    ->label('Traccia')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn ($record): ?string => $record->tracking_url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record): bool => !empty($record->tracking_url)),
                    
                Action::make('update_status')
                    ->label('Aggiorna Stato')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Nuovo Stato')
                            ->required()
                            ->options([
                                'pending' => 'In preparazione',
                                'shipped' => 'Spedito',
                                'in_transit' => 'In transito',
                                'delivered' => 'Consegnato',
                                'failed' => 'Fallito',
                                'returned' => 'Restituito',
                            ]),
                            
                        Forms\Components\DateTimePicker::make('delivered_at')
                            ->label('Data Consegna')
                            ->visible(fn (Forms\Get $get): bool => $get('status') === 'delivered')
                            ->native(false),
                    ])
                    ->action(function ($record, array $data) {
                        $updateData = ['status' => $data['status']];
                        
                        if ($data['status'] === 'delivered' && isset($data['delivered_at'])) {
                            $updateData['delivered_at'] = $data['delivered_at'];
                        }
                        
                        $record->update($updateData);
                    }),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}