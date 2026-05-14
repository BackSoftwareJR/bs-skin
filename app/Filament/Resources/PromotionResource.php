<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    
    protected static ?string $navigationGroup = 'Marketing';
    
    protected static ?string $navigationLabel = 'Promozioni';
    
    protected static ?string $modelLabel = 'Promozione';
    
    protected static ?string $pluralModelLabel = 'Promozioni';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Base')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Descrizione')
                            ->rows(3)
                            ->maxLength(1000),
                    ]),
                    
                Forms\Components\Section::make('Configurazione Sconto')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->required()
                            ->options([
                                'automatic' => 'Automatica',
                                'manual' => 'Manuale (con codice)',
                            ])
                            ->default('automatic'),
                            
                        Forms\Components\Select::make('discount_type')
                            ->label('Tipo Sconto')
                            ->required()
                            ->options([
                                'percentage' => 'Percentuale',
                                'fixed' => 'Importo Fisso',
                                'free_shipping' => 'Spedizione Gratuita',
                            ]),
                            
                        Forms\Components\TextInput::make('discount_value')
                            ->label('Valore Sconto')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->suffix(fn (Forms\Get $get): string => match ($get('discount_type')) {
                                'percentage' => '%',
                                'fixed' => '€',
                                default => '',
                            })
                            ->visible(fn (Forms\Get $get): bool => $get('discount_type') !== 'free_shipping'),
                            
                        Forms\Components\TextInput::make('priority')
                            ->label('Priorità')
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->helperText('Numero più basso = priorità più alta'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Targeting')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Cliente Specifico')
                            ->searchable()
                            ->preload()
                            ->getSearchResultsUsing(fn (string $search): array => 
                                Customer::where('email', 'like', "%{$search}%")
                                    ->orWhere('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->pluck('full_name_and_email', 'id')
                                    ->toArray()
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->full_name_and_email)
                            ->helperText('Lascia vuoto per applicare a tutti i clienti'),
                    ]),
                    
                Forms\Components\Section::make('Validità')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Inizio')
                            ->required()
                            ->native(false)
                            ->default(now()),
                            
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Scadenza')
                            ->required()
                            ->native(false)
                            ->after('starts_at'),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Attiva')
                            ->default(true),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Regole Avanzate')
                    ->schema([
                        Forms\Components\Repeater::make('rules_json')
                            ->label('Regole')
                            ->schema([
                                Forms\Components\TextInput::make('key')
                                    ->label('Chiave')
                                    ->required()
                                    ->placeholder('es: min_amount')
                                    ->maxLength(255),
                                    
                                Forms\Components\TextInput::make('value')
                                    ->label('Valore')
                                    ->required()
                                    ->placeholder('es: 50')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => 
                                ($state['key'] ?? '') . ': ' . ($state['value'] ?? '')
                            )
                            ->helperText('Regole personalizzate per l\'applicazione della promozione'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'automatic' => 'Automatica',
                        'manual' => 'Manuale',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'automatic' => 'success',
                        'manual' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Sconto')
                    ->formatStateUsing(fn (string $state, $record): string => match ($state) {
                        'percentage' => $record->discount_value . '%',
                        'fixed' => '€' . number_format($record->discount_value, 2),
                        'free_shipping' => 'Spedizione Gratuita',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Cliente')
                    ->placeholder('Tutti i clienti')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Inizio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Scadenza')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record): string => $record->expires_at->isPast() ? 'danger' : 'success'),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Attiva'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'automatic' => 'Automatica',
                        'manual' => 'Manuale',
                    ]),
                    
                Tables\Filters\SelectFilter::make('discount_type')
                    ->label('Tipo Sconto')
                    ->options([
                        'percentage' => 'Percentuale',
                        'fixed' => 'Importo Fisso',
                        'free_shipping' => 'Spedizione Gratuita',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attiva')
                    ->placeholder('Tutte')
                    ->trueLabel('Solo attive')
                    ->falseLabel('Solo inattive'),
            ])
            ->actions([
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}