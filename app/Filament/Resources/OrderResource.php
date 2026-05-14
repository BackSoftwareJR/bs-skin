<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Ordini';
    
    protected static ?string $modelLabel = 'ordine';
    
    protected static ?string $pluralModelLabel = 'ordini';
    
    protected static ?string $navigationGroup = 'E-commerce';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Ordine')
                    ->description('Gestione stato e note ordine')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Numero Ordine')
                            ->disabled(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Stato Ordine')
                            ->options(OrderStatus::class)
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Select::make('payment_status')
                            ->label('Stato Pagamento')
                            ->options(PaymentStatus::class)
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Textarea::make('notes_admin')
                            ->label('Note Amministrative')
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
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Numero')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pagamento')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'authorized' => 'info',
                        'captured' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('Totale')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data Ordine')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options(OrderStatus::class),
                
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Stato Pagamento')
                    ->options(PaymentStatus::class),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Da data'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Fino a data'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Dettagli Ordine')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Numero Ordine'),
                        
                        Infolists\Components\TextEntry::make('status')
                            ->label('Stato')
                            ->badge(),
                        
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Data Ordine')
                            ->dateTime('d/m/Y H:i'),
                        
                        Infolists\Components\TextEntry::make('notes_admin')
                            ->label('Note Amministrative')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                
                Infolists\Components\Section::make('Riepilogo Finanziario')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotale')
                            ->money('EUR'),
                        
                        Infolists\Components\TextEntry::make('discount_total')
                            ->label('Sconto')
                            ->money('EUR'),
                        
                        Infolists\Components\TextEntry::make('shipping_total')
                            ->label('Spedizione')
                            ->money('EUR'),
                        
                        Infolists\Components\TextEntry::make('tax_total')
                            ->label('Tasse')
                            ->money('EUR'),
                        
                        Infolists\Components\TextEntry::make('total')
                            ->label('Totale')
                            ->money('EUR')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
            RelationManagers\ShipmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Ordini creati solo dal frontend
    }
}