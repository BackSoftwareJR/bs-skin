<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundResource\Pages;
use App\Models\Refund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RefundResource extends Resource
{
    protected static ?string $model = Refund::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    
    protected static ?string $navigationLabel = 'Rimborsi';
    
    protected static ?string $modelLabel = 'rimborso';
    
    protected static ?string $pluralModelLabel = 'rimborsi';
    
    protected static ?string $navigationGroup = 'E-commerce';
    
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dettagli Rimborso')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Ordine')
                            ->relationship('order', 'order_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('payment_id')
                            ->label('Pagamento')
                            ->relationship('payment', 'external_id')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Importo')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0.01)
                            ->step(0.01),
                        
                        Forms\Components\Select::make('reason')
                            ->label('Motivo')
                            ->options([
                                'customer_request' => 'Richiesta Cliente',
                                'product_defect' => 'Prodotto Difettoso',
                                'wrong_item' => 'Articolo Sbagliato',
                                'damaged_shipping' => 'Danneggiato in Spedizione',
                                'not_as_described' => 'Non Conforme alla Descrizione',
                                'duplicate_charge' => 'Addebito Duplicato',
                                'fraudulent' => 'Fraudolento',
                                'other' => 'Altro',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Select::make('status')
                            ->label('Stato')
                            ->options([
                                'pending' => 'In Attesa',
                                'approved' => 'Approvato',
                                'processed' => 'Elaborato',
                                'completed' => 'Completato',
                                'rejected' => 'Rifiutato',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        
                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Data Elaborazione'),
                        
                        Forms\Components\Textarea::make('reason_details')
                            ->label('Dettagli Motivo')
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
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Importo')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'customer_request' => 'Richiesta Cliente',
                        'product_defect' => 'Prodotto Difettoso',
                        'wrong_item' => 'Articolo Sbagliato',
                        'damaged_shipping' => 'Danneggiato in Spedizione',
                        'not_as_described' => 'Non Conforme',
                        'duplicate_charge' => 'Addebito Duplicato',
                        'fraudulent' => 'Fraudolento',
                        'other' => 'Altro',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'processed' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Data Elaborazione')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Richiesto')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'pending' => 'In Attesa',
                        'approved' => 'Approvato',
                        'processed' => 'Elaborato',
                        'completed' => 'Completato',
                        'rejected' => 'Rifiutato',
                    ]),
                
                Tables\Filters\SelectFilter::make('reason')
                    ->label('Motivo')
                    ->options([
                        'customer_request' => 'Richiesta Cliente',
                        'product_defect' => 'Prodotto Difettoso',
                        'wrong_item' => 'Articolo Sbagliato',
                        'damaged_shipping' => 'Danneggiato in Spedizione',
                        'not_as_described' => 'Non Conforme',
                        'duplicate_charge' => 'Addebito Duplicato',
                        'fraudulent' => 'Fraudolento',
                        'other' => 'Altro',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefunds::route('/'),
            'create' => Pages\CreateRefund::route('/create'),
            'view' => Pages\ViewRefund::route('/{record}'),
            'edit' => Pages\EditRefund::route('/{record}/edit'),
        ];
    }
}