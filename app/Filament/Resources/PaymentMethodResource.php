<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Impostazioni';
    
    protected static ?string $navigationLabel = 'Metodi di Pagamento';
    
    protected static ?string $modelLabel = 'Metodo di Pagamento';
    
    protected static ?string $pluralModelLabel = 'Metodi di Pagamento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Codice')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn ($record) => $record !== null)
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('provider')
                    ->label('Provider')
                    ->required()
                    ->options([
                        'manual' => 'Manuale',
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                    ]),
                    
                Forms\Components\Textarea::make('instructions')
                    ->label('Istruzioni')
                    ->rows(3)
                    ->maxLength(1000)
                    ->helperText('Istruzioni mostrate al cliente durante il checkout'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordine')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                    
                Forms\Components\TextInput::make('icon_path')
                    ->label('Percorso Icona')
                    ->placeholder('Es: /images/payment-icons/visa.svg')
                    ->maxLength(255)
                    ->helperText('Percorso relativo all\'icona del metodo di pagamento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'manual' => 'gray',
                        'stripe' => 'blue',
                        'paypal' => 'yellow',
                        default => 'gray',
                    }),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Attivo'),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attivo')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo inattivi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}