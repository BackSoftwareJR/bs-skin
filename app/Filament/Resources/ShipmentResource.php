<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Spedizioni';
    
    protected static ?string $modelLabel = 'spedizione';
    
    protected static ?string $pluralModelLabel = 'spedizioni';
    
    protected static ?string $navigationGroup = 'E-commerce';
    
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dettagli Spedizione')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Ordine')
                            ->relationship('order', 'order_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('carrier')
                            ->label('Corriere')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Numero di Tracciamento')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('tracking_url')
                            ->label('URL Tracciamento')
                            ->url()
                            ->maxLength(500),
                        
                        Forms\Components\Select::make('status')
                            ->label('Stato')
                            ->options([
                                'preparing' => 'In Preparazione',
                                'shipped' => 'Spedito',
                                'in_transit' => 'In Transito',
                                'delivered' => 'Consegnato',
                                'failed' => 'Consegna Fallita',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\DateTimePicker::make('shipped_at')
                            ->label('Data Spedizione'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Note')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Ordine')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('carrier')
                    ->label('Corriere')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'preparing' => 'warning',
                        'shipped' => 'info',
                        'in_transit' => 'info',
                        'delivered' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('shipped_at')
                    ->label('Data Spedizione')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'preparing' => 'In Preparazione',
                        'shipped' => 'Spedito',
                        'in_transit' => 'In Transito',
                        'delivered' => 'Consegnato',
                        'failed' => 'Consegna Fallita',
                    ]),
                
                Tables\Filters\SelectFilter::make('carrier')
                    ->label('Corriere')
                    ->options(function (): array {
                        return Shipment::distinct('carrier')
                            ->pluck('carrier', 'carrier')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('copy_tracking')
                    ->label('Copia Tracking')
                    ->icon('heroicon-o-clipboard')
                    ->action(function (Shipment $record) {
                        // JavaScript per copiare negli appunti
                        return redirect()->back()->with('notification', [
                            'title' => 'Tracking copiato',
                            'body' => "Numero di tracciamento: {$record->tracking_number}",
                        ]);
                    })
                    ->visible(fn (Shipment $record): bool => !empty($record->tracking_number)),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('shipped_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'view' => Pages\ViewShipment::route('/{record}'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}