<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Clienti';
    
    protected static ?string $modelLabel = 'cliente';
    
    protected static ?string $pluralModelLabel = 'clienti';
    
    protected static ?string $navigationGroup = 'E-commerce';
    
    protected static ?int $navigationSort = 20;
    
    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Personali')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('last_name')
                            ->label('Cognome')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefono')
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Data di Nascita'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Consensi e Privacy')
                    ->schema([
                        Forms\Components\Toggle::make('marketing_consent')
                            ->label('Consenso Marketing'),
                        
                        Forms\Components\DateTimePicker::make('marketing_consent_at')
                            ->label('Data Consenso Marketing')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nome Completo')
                    ->getStateUsing(fn (Customer $record): string => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_orders')
                    ->label('Ordini')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Totale Speso')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data Iscrizione')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Stato')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo disattivati')
                    ->native(false),
                
                Tables\Filters\TernaryFilter::make('marketing_consent')
                    ->label('Consenso Marketing')
                    ->trueLabel('Con consenso')
                    ->falseLabel('Senza consenso')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('deactivate')
                    ->label('Disattiva')
                    ->icon('heroicon-o-user-minus')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn (Customer $record) => $record->update(['is_active' => false]))
                    ->visible(fn (Customer $record): bool => $record->is_active),
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
                Infolists\Components\Section::make('Informazioni Cliente')
                    ->schema([
                        Infolists\Components\TextEntry::make('first_name')
                            ->label('Nome'),
                        
                        Infolists\Components\TextEntry::make('last_name')
                            ->label('Cognome'),
                        
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telefono')
                            ->copyable(),
                        
                        Infolists\Components\TextEntry::make('date_of_birth')
                            ->label('Data di Nascita')
                            ->date('d/m/Y'),
                        
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Attivo')
                            ->boolean(),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Statistiche')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_orders')
                            ->label('Numero Ordini')
                            ->numeric(),
                        
                        Infolists\Components\TextEntry::make('total_spent')
                            ->label('Totale Speso')
                            ->money('EUR'),
                        
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Cliente dal')
                            ->dateTime('d/m/Y H:i'),
                        
                        Infolists\Components\TextEntry::make('last_login_at')
                            ->label('Ultimo Accesso')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Mai effettuato'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CustomerAddressesRelationManager::class,
            RelationManagers\CustomerOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}