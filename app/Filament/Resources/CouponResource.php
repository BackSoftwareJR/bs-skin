<?php

namespace App\Filament\Resources;

use App\Enums\CouponType;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationLabel = 'Coupon';
    
    protected static ?string $modelLabel = 'coupon';
    
    protected static ?string $pluralModelLabel = 'coupon';
    
    protected static ?string $navigationGroup = 'Catalogo';
    
    protected static ?int $navigationSort = 50;
    
    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Base')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Codice Coupon')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-o-arrow-path')
                                    ->action(fn (Forms\Set $set) => 
                                        $set('code', strtoupper(Str::random(8)))
                                    )
                            ),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipo Sconto')
                            ->options(CouponType::class)
                            ->required()
                            ->reactive()
                            ->native(false),
                        
                        Forms\Components\TextInput::make('value')
                            ->label('Valore')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->suffix(fn (Forms\Get $get): string => 
                                $get('type') === 'percentage' ? '%' : '€'
                            ),
                        
                        Forms\Components\TextInput::make('min_order_amount')
                            ->label('Importo Minimo Ordine')
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(0.01),
                        
                        Forms\Components\TextInput::make('max_discount')
                            ->label('Sconto Massimo')
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Solo per sconti percentuali'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Applicabilità')
                    ->schema([
                        Forms\Components\Select::make('applies_to')
                            ->label('Si Applica A')
                            ->options([
                                'all' => 'Tutti i prodotti',
                                'specific_products' => 'Prodotti specifici',
                                'categories' => 'Categorie specifiche',
                                'brands' => 'Marchi specifici',
                                'customer' => 'Cliente specifico',
                            ])
                            ->required()
                            ->reactive()
                            ->native(false),
                        
                        Forms\Components\Select::make('customer_id')
                            ->label('Cliente Specifico')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get): bool => $get('applies_to') === 'customer'),
                        
                        Forms\Components\Hidden::make('applicable_ids')
                            ->default([])
                            ->helperText('IDs dei prodotti/categorie/marchi se applicabile'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Limiti di Utilizzo')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit_global')
                            ->label('Limite Utilizzi Globale')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Massimo numero di utilizzi totali'),
                        
                        Forms\Components\TextInput::make('usage_limit_per_customer')
                            ->label('Limite per Cliente')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->helperText('Massimo utilizzi per singolo cliente'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Validità Temporale')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Valido dal')
                            ->default(now()),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Scade il')
                            ->after('starts_at'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Descrizione')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descrizione/Note')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => 
                        $state === 'percentage' ? 'Percentuale' : 'Fisso'
                    )
                    ->badge()
                    ->color(fn (string $state): string => 
                        $state === 'percentage' ? 'success' : 'info'
                    ),
                
                Tables\Columns\TextColumn::make('value')
                    ->label('Valore')
                    ->formatStateUsing(function (Coupon $record): string {
                        return $record->type === 'percentage' 
                            ? $record->value . '%' 
                            : '€' . number_format($record->value, 2);
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('usage_stats')
                    ->label('Utilizzi')
                    ->getStateUsing(function (Coupon $record): string {
                        $used = $record->usage_count ?? 0;
                        $limit = $record->usage_limit_global ?? '∞';
                        return "{$used}/{$limit}";
                    }),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Scadenza')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn (Coupon $record): string => 
                        $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'gray'
                    ),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(CouponType::class),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Stato')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo disattivi'),
                
                Tables\Filters\Filter::make('expires_at')
                    ->label('Scadenza')
                    ->form([
                        Forms\Components\DatePicker::make('expires_after')
                            ->label('Scade dopo'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['expires_after'],
                            fn (Builder $query, $date): Builder => 
                                $query->where('expires_at', '>=', $date)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplica')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Coupon $record) {
                        $newCoupon = $record->replicate();
                        $newCoupon->code = $record->code . '-COPY';
                        $newCoupon->usage_count = 0;
                        $newCoupon->save();
                        
                        return redirect()->route('filament.admin.resources.coupons.edit', $newCoupon);
                    }),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Attiva')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_active' => true])
                        ),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Disattiva')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_active' => false])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view' => Pages\ViewCoupon::route('/{record}'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}